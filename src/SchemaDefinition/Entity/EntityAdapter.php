<?php declare(strict_types = 1);

namespace Apitte\OpenApi\SchemaDefinition\Entity;

use Apitte\Core\Exception\Logical\InvalidArgumentException;
use Apitte\Core\Exception\Logical\InvalidStateException;
use DateTimeInterface;
use Nette\Utils\Reflection;
use Nette\Utils\Strings;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunctionAbstract;
use ReflectionProperty;
use Reflector;
use Apitte\OpenApi\SchemaDefinition\Entity\IEntityAdapter;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Validator\Validation;
use Nette\SmartObject;
use Symfony\Component\Validator\Constraints as Assert;

class EntityAdapter implements IEntityAdapter
{
	use SmartObject;

	/** @var Reader */
	private $reader;

	public function __construct(Reader $reader)
	{
		$this->reader = $reader;
	}

	/**
	 * @return mixed[]
	 */
	public function getMetadata(string $type): array
	{
		// Ignore brackets (not supported by schema)
		$type = str_replace(['(', ')'], '', $type);

		// Normalize null type
		$type = str_replace('?', 'null|', $type);

		$usesUnionType = Strings::contains($type, '|');
		$usesIntersectionType = Strings::contains($type, '&');

		// Get schema for all possible types
		if ($usesUnionType || $usesIntersectionType) {
			$types = preg_split('#([&|])#', $type, -1, PREG_SPLIT_NO_EMPTY);

			// Filter out duplicate definitions
			$types = array_map(function (string $type): string {
				return $this->normalizeType($type);
			}, $types);
			$types = array_unique($types);

			$metadata = [];
			$nullKey = array_search('null', $types, true);

			// Remove null from other types
			if ($nullKey !== false) {
				unset($types[$nullKey]);
				$metadata['nullable'] = true;
			}

			// Types contain single, nullable value
			if (count($types) === 1) {
				return array_merge($metadata, $this->getMetadata(current($types)));
			}

			$resolvedTypes = [];
			foreach ($types as $subType) {
				$resolvedTypes[] = $this->getMetadata($subType);
			}

			if ($usesUnionType && $usesIntersectionType) {
				$schemaCombination = 'anyOf';
			} elseif ($usesUnionType) {
				$schemaCombination = 'oneOf';
			} else {
				$schemaCombination = 'allOf';
			}

			$metadata[$schemaCombination] = $resolvedTypes;

			return $metadata;
		}

		// Get schema for array
		if (Strings::endsWith($type, '[]')) {
			$subType = Strings::replace($type, '#\\[\\]#');

			return [
				'type' => 'array',
				'items' => $this->getMetadata($subType),
			];
		}

		// Get schema for class
		if (class_exists($type)) {
			// String is converted to DateTimeInterface internally in core
			if (is_subclass_of($type, DateTimeInterface::class)) {
				return [
					'type' => 'string',
					'format' => 'date-time',
				];
			}

			$properties = $this->getProperties($type);
			$required = [];
			foreach ($properties as $propertyName => &$values){
				if (array_key_exists('required', $values)){
					unset($values['required']);
					$required[] =  $propertyName;
				}
			}

			return [
				'type' => 'object',
				'properties' => $properties,
			] + ($required ? [
				'required' => $required
			] : []);
		}

		$lower = strtolower($type);

		// For php and phpstan is mixed absolutely anything, including null -> write in schema property accepts anything
		if ($lower === 'mixed') {
			return [
				'nullable' => true,
			];
		}

		if ($lower === 'object' || interface_exists($type)) {
			return [
				'type' => 'object',
			];
		}

		// Get schema for scalar type
		return [
			'type' => $this->phpScalarTypeToOpenApiType($type),
		];
	}

	/**
	 * @return mixed[]
	 */
	protected function getProperties(string $type): array
	{
		if (!class_exists($type)) {
			return [];
		}

		$ref = new ReflectionClass($type);
		$properties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
		$data = [];

		foreach ($properties as $property) {
			$propertyType = $this->getPropertyType($property) ?? 'string';

			// Self-reference not supported
			if ($propertyType === $type) {
				$propertyType = 'object';
			}

			$data[$property->getName()] = $this->getMetadata($propertyType) + $this->getExtendedMetadata($property);
		}

		return $data;
	}

	private function getPropertyType(ReflectionProperty $property): ?string
	{
		//TODO - fix typed properties support
		//if (PHP_VERSION_ID >= 70400 && ($type = Reflection::getPropertyType($property) !== null)) {
		//	return ($property->getType()->allowsNull() ? '?' : '') . $type;
		//}

		$annotation = $this->parseAnnotation($property, 'var');

		if ($annotation === null) {
			return null;
		}

		if (($type = preg_replace('#\s.*#', '', $annotation)) !== null) {
			$class = Reflection::getPropertyDeclaringClass($property);

			return preg_replace_callback('#[\w\\\\]+#', function ($m) use ($class):string {
				static $phpdocKnownTypes = [
					// phpcs:disable
					'bool', 'boolean', 'false', 'true',
					'int', 'integer',
					'float', 'double',
					'string', 'numeric', 'mixed', 'object',
					// phpcs:enable
				];

				$lower = $m[0];

				if (in_array($lower, $phpdocKnownTypes, true)) {
					return $this->normalizeType($lower);
				}

				// Self-reference not supported
				if (in_array($lower, ['static', 'self'], true)) {
					return 'object';
				}

				return Reflection::expandClassName($m[0], $class);
			}, $type);
		}

		return null;
	}

	/**
	 * @param ReflectionClass|ReflectionClassConstant|ReflectionProperty|ReflectionFunctionAbstract $ref
	 */
	private function parseAnnotation(Reflector $ref, string $name): ?string
	{
		if (!Reflection::areCommentsAvailable()) {
			throw new InvalidStateException('You have to enable phpDoc comments in opcode cache.');
		}

		$re = '#[\s*]@' . preg_quote($name, '#') . '(?=\s|$)(?:[ \t]+([^@\s]\S*))?#';
		if ($ref->getDocComment() && preg_match($re, trim($ref->getDocComment(), '/*'), $m)) {
			return $m[1] ?? null;
		}

		return null;
	}

	/**
	 * Converts scalar types (including phpdoc types and reserved words) to open api types
	 */
	protected function phpScalarTypeToOpenApiType(string $type): string
	{
		// Mixed and null not included, they are handled their own special way
		static $map = [
			'int' => 'integer',
			'float' => 'number',
			'bool' => 'boolean',
			'string' => 'string',
		];

		$type = $this->normalizeType($type);
		$lower = strtolower($type);

		if (!array_key_exists($lower, $map)) {
			throw new InvalidArgumentException(sprintf('Unsupported or unconvertible variable type \'%s\'', $type));
		}

		return $map[$lower];
	}

	protected function normalizeType(string $type): string
	{
		static $map = [
			'integer' => 'int',
			'double' => 'float',
			'numeric' => 'float',
			'boolean' => 'bool',
			'false' => 'bool',
			'true' => 'bool',
		];

		return $map[strtolower($type)] ?? $type;
	}

	private $_validator;
	protected function getValidator(){
		if ($this->_validator === null){
			$this->_validator = Validation::createValidatorBuilder()
				->enableAnnotationMapping($this->reader)
				->getValidator()
			;
		}
		return $this->_validator;
	}

	/**
	 * @param ReflectionClass|ReflectionClassConstant|ReflectionProperty|ReflectionFunctionAbstract $ref
	 */
	protected function getExtendedMetadata(Reflector $ref): array
	{
		try {
			$convertableType = $this->phpScalarTypeToOpenApiType($this->getPropertyType($ref));
		} catch (InvalidArgumentException $e){
			$convertableType = null;
		}
		
		$metadata = $this->validator->getMetadataFor($ref->getDeclaringClass()->getName());
		$constraints = [];
		if ($constraintsMetadata = $metadata->getPropertyMetadata($ref->getName())){
			foreach($constraintsMetadata as $constraintMetadata){
				foreach ($constraintMetadata->constraints as $constraint){
					if ($constraint instanceof Assert\Length){
						$constraints['minLength'] = $constraint->min;
						$constraints['maxLength'] = $constraint->max;
					} elseif ($constraint instanceof Assert\Choice){
						$constraints['enum'] = $constraint->choices;
					} elseif ($constraint instanceof Assert\Regex){
						$constraints['pattern'] = $constraint->pattern;
					} elseif ($constraint instanceof Assert\Email){
						$constraints['format'] = 'email';
					} elseif ($constraint instanceof Assert\NotNull){
						$constraints['required'] = true;
					} elseif ($constraint instanceof Assert\NotBlank){
						$constraints['required'] = true;
					}
				}
			}
			
		}
		
		$docBlock = $this->getDocBlockFactory()->create($ref->getDocComment());
		return array_filter($constraints + [
			'example' => $this->convertType(join("\n", $docBlock->getTagsByName('example')), $convertableType),
			'title' => (string)$docBlock->getSummary(),
			'description' => (string)$docBlock->getDescription(),
		]);
	}
	
	protected function convertType($value, $type){
		switch ($type){
			case 'integer' : return (int)$value;
			case 'number' : return round((float)$value, 10);
			case 'boolean' : return (bool)$value;
		}
		
		return $value;
	}
	
	private $_docBlockFactory;
	protected function getDocBlockFactory(){
		if ($this->_docBlockFactory === null){
			$this->_docBlockFactory = \phpDocumentor\Reflection\DocBlockFactory::createInstance();
		}
		return $this->_docBlockFactory;
	}
}
