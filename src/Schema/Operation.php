<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Operation
{

	/** @var string[] */
	private $tags = [];

	/** @var string|null */
	private $summary;

	/** @var string|null */
	private $description;

	/** @var ExternalDocumentation|null */
	private $externalDocs;

	/** @var string|null */
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

	public function __construct(Responses $responses)
	{
		$this->responses = $responses;
	}

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): Operation
	{
		$responses = Responses::fromArray($data['responses']);
		$operation = new Operation($responses);
		if (isset($data['requestBody'])) {
			$operation->requestBody = RequestBody::fromArray($data['requestBody']);
		}
		$operation->setOperationId($data['operationId'] ?? null);
		$operation->setTags($data['tags'] ?? []);
		$operation->setSummary($data['summary'] ?? null);
		$operation->setDescription($data['description'] ?? null);
		if (isset($data['externalDocs'])) {
			$operation->setExternalDocs(ExternalDocumentation::fromArray($data['externalDocs']));
		}
		if (isset($data['parameters'])) {
			foreach ($data['parameters'] as $parameterData) {
				if (isset($parameterData['$ref'])) {
					$operation->setParameter(new Reference($parameterData['$ref']));
					continue;
				}
				$operation->setParameter(Parameter::fromArray($parameterData));
			}
		}
		if (isset($data['requestBody'])) {
			$operation->requestBody = RequestBody::fromArray($data['requestBody']);
		}
		if (isset($data['security'])) {
			foreach ($data['security'] as $securityRequirmentData) {
				$operation->setSecurityRequirement(SecurityRequirement::fromArray($securityRequirmentData));
			}
		}
		return $operation;
	}

	public function setOperationId(?string $operationId): void
	{
		$this->operationId = $operationId;
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

	public function setExternalDocs(?ExternalDocumentation $externalDocs): void
	{
		$this->externalDocs = $externalDocs;
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

	public function setSecurityRequirement(SecurityRequirement $securityRequirement): void
	{
		$this->security[] = $securityRequirement;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		if (count($this->tags) > 0) {
			$data['tags'] = $this->tags;
		}
		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}
		if ($this->description !== null) {
			$data['description'] = $this->description;
		}
		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}
		if ($this->operationId !== null) {
			$data['operationId'] = $this->operationId;
		}
		foreach ($this->parameters as $parameter) {
			$data['parameters'][] = $parameter->toArray();
		}
		if ($this->requestBody !== null) {
			$data['requestBody'] = $this->requestBody->toArray();
		}
		foreach ($this->security as $securityRequirement) {
			$data['security'][] = $securityRequirement->toArray();
		}
		$data['responses'] = $this->responses->toArray();
		//			'deprecated' => $this->deprecated,
		//			'servers' => Utils::fromArray($this->servers),

		return $data;
	}

	/**
	 * @return string[]
	 */
	public function getTags(): array
	{
		return $this->tags;
	}

	public function getSummary(): ?string
	{
		return $this->summary;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function getExternalDocs(): ?ExternalDocumentation
	{
		return $this->externalDocs;
	}

	public function getOperationId(): ?string
	{
		return $this->operationId;
	}

	/**
	 * @return Parameter[]|Reference[]
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @return Reference|RequestBody|null
	 */
	public function getRequestBody()
	{
		return $this->requestBody;
	}

	public function getResponses(): Responses
	{
		return $this->responses;
	}

	/**
	 * @return Reference[]|Callback[]
	 */
	public function getCallbacks()
	{
		return $this->callbacks;
	}

	public function isDeprecated(): bool
	{
		return $this->deprecated;
	}

	/**
	 * @return SecurityRequirement[]
	 */
	public function getSecurity(): array
	{
		return $this->security;
	}

	/**
	 * @return Server[]
	 */
	public function getServers(): array
	{
		return $this->servers;
	}

}
