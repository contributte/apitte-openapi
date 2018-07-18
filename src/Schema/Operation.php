<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Operation implements IOpenApiObject
{

	/** @var string[] */
	private $tags = [];

	/** @var string|null */
	private $summary;

	/** @var string|null */
	private $description;

	/** @var ExternalDocumentation|null */
	private $externalDocs;

	/** @var string */
	private $operationId;

	/** @var Parameter[]|Reference[] */
	private $parameters = [];

	/** @var RequestBody|Reference|null */
	private $requestBody;

	/** @var Responses */
	private $responses;

	/** @var Callback[]|Reference[] */
	private $callbacks;

	/** @var bool */
	private $deprecated = false;

	/** @var SecurityRequirement[] */
	private $security = [];

	/** @var Server[] */
	private $servers = [];

	public function __construct(string $operationId, Responses $responses)
	{
		$this->operationId = $operationId;
		$this->responses = $responses;
	}

	/**
	 * @param string[] $tags
	 */
	public function setTags(array $tags): void
	{
		$this->tags = $tags;
	}

	public function setSummary(?string $summary): void
	{
		$this->summary = $summary;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	/**
	 * @param Parameter|Reference $parameter
	 */
	public function setParameter($parameter): void
	{
		$this->parameters[] = $parameter;
	}

	public function setRequestBody(?RequestBody $requestBody): void
	{
		$this->requestBody = $requestBody;
	}

	public function setDeprecated(bool $deprecated): void
	{
		$this->deprecated = $deprecated;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
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
