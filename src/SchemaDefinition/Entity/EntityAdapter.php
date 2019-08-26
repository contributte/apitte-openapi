<?php declare(strict_types = 1);

namespace Apitte\OpenApi\SchemaDefinition\Entity;

use Nette\Utils\Strings;
use ReflectionClass;
use ReflectionProperty;

class EntityAdapter implements IEntityAdapter
{

	/**
	 * @return mixed[]
	 */
	public function getMetadata(string $type): array
	{
		if (Strings::endsWith($type, '[]')) {
			$type = Strings::replace($type, '#\\[\\]#');
			return [
				'type' => 'array',
				'items' => [
					'type' => 'object',
					'properties' => $this->getProperties($type),
				],
			];
		}
		return [
			'type' => 'object',
			'properties' => $this->getProperties($type),
		];
	}

	/**
	 * @return mixed[]
	 */
	protected function getProperties(string $type): array
	{
		$ref = new ReflectionClass($type);
		$properties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
		$data = [];
		foreach ($properties as $property) {
			// TODO resolve type
			$data[$property->getName()] = ['type' => 'string'];
		}
		return $data;
	}

}
