<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Components implements IOpenApiObject
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
	 * @param Schema|Reference $schema
	 */
	public function setSchema(string $name, $schema): void
	{
		$this->schemas[$name] = $schema;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'schemas' => Utils::fromArray($this->schemas),
			'responses' => Utils::fromArray($this->responses),
			'parameters' => Utils::fromArray($this->parameters),
			'examples' => Utils::fromArray($this->examples),
			'requestBodies' => Utils::fromArray($this->requestBodies),
			'headers' => Utils::fromArray($this->headers),
			'securitySchemes' => Utils::fromArray($this->securitySchemes),
			'links' => Utils::fromArray($this->links),
			'callbacks' => Utils::fromArray($this->callbacks),
		]);
	}

}
