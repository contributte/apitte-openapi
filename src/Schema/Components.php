<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Components
{

	/** @var Schema[]|Reference[] */
	private $schemas = [];

	/** @var Response[]|Reference[] */
	private $responses = [];

	/** @var Parameter[]|Reference[] */
	private $parameters = [];

	/** @var Example[]|Reference[] */
	private $examples = [];

	/** @var RequestBody[]|Reference[] */
	private $requestBodies = [];

	/** @var Header[]|Reference[] */
	private $headers = [];

	/** @var SecurityScheme[]|Reference[] */
	private $securitySchemes = [];

	/** @var Link[]|Reference[] */
	private $links = [];

	/** @var Callback[]|Reference[] */
	private $callbacks = [];

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): Components
	{
		$components = new Components();
		if (isset($data['schemas'])) {
			foreach ($data['schemas'] as $schemaKey => $schemaData) {
				$components->setSchema($schemaKey, Schema::fromArray($schemaData));
			}
		}
		if (isset($data['parameters'])) {
			foreach ($data['parameters'] as $parameterKey => $parameterData) {
				$components->setParameter($parameterKey, Parameter::fromArray($parameterData));
			}
		}
		if (isset($data['securitySchemes'])) {
			foreach ($data['securitySchemes'] as $securitySchemeKey => $securitySchemeData) {
				$components->setSecurityScheme($securitySchemeKey, SecurityScheme::fromArray($securitySchemeData));
			}
		}
		return $components;
	}

	/**
	 * @param Schema|Reference $schema
	 */
	public function setSchema(string $name, $schema): void
	{
		$this->schemas[$name] = $schema;
	}

	/**
	 * @param Parameter|Reference $parameter
	 */
	public function setParameter(string $name, $parameter): void
	{
		$this->parameters[$name] = $parameter;
	}

	/**
	 * @param SecurityScheme|Reference $securityScheme
	 */
	public function setSecurityScheme(string $name, $securityScheme): void
	{
		$this->securitySchemes[$name] = $securityScheme;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		foreach ($this->schemas as $schemaKey => $schema) {
			$data['schemas'][$schemaKey] = $schema->toArray();
		}
		foreach ($this->parameters as $parameterKey => $parameter) {
			$data['parameters'][$parameterKey] = $parameter->toArray();
		}
		foreach ($this->securitySchemes as $securitySchemeKey => $securityScheme) {
			$data['securitySchemes'][$securitySchemeKey] = $securityScheme->toArray();
		}
		return $data;
	}

}
