<?php

namespace Apitte\OpenApi\Schema;

class Operation implements IOpenApiObject
{

	/** @var string[] */
	private $tags = [];

	/** @var string|NULL */
	private $summary;

	/** @var string|NULL */
	private $description;

	/** @var ExternalDocumentation|NULL */
	private $externalDocs;

	/** @var string */
	private $operationId;

	/** @var Parameter[]|Reference[] */
	private $parameters = [];

	/** @var RequestBody|Reference|NULL */
	private $requestBody;

	/** @var Responses */
	private $responses;

	//Map[string, Callback Object | Reference Object]
	private $callbacks;

	/** @var bool */
	private $deprecated = FALSE;

	/** @var SecurityRequirement[] */
	private $security = [];

	/** @var Server[] */
	private $servers = [];

	/**
	 * @param string $operationId
	 * @param Responses $responses
	 */
	public function __construct($operationId, Responses $responses)
	{
		$this->operationId = $operationId;
		$this->responses = $responses;
	}

	//Setters

	/**
	 * @param string[] $tags
	 * @return void
	 */
	public function setTags($tags)
	{
		$this->tags = $tags;
	}

	/**
	 * @param string|NULL $summary
	 * @return void
	 */
	public function setSummary($summary)
	{
		$this->summary = $summary;
	}

	/**
	 * @param string|NULL $description
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @param Parameter|Reference $parameter
	 * @return void
	 */
	public function setParameter($parameter)
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * @param RequestBody|NULL $requestBody
	 * @return void
	 */
	public function setRequestBody($requestBody)
	{
		$this->requestBody = $requestBody;
	}

	/**
	 * @param bool $deprecated
	 * @return void
	 */
	public function setDeprecated($deprecated)
	{
		$this->deprecated = $deprecated;
	}

	//Get

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'tags' => $this->tags,
			'summary' => $this->summary,
			'description' => $this->description,
			'externalDocs' => Utils::fromNullable($this->externalDocs),
			'operationId' => $this->operationId,
			'parameters' => Utils::fromArray($this->parameters),
			'requestBody' => Utils::fromNullable($this->requestBody),
			'responses' => $this->responses->toArray(),
			'deprecated' => $this->deprecated,
			'security' => Utils::fromArray($this->security),
			'servers' => Utils::fromArray($this->servers),
		]);
	}

}
