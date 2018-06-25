<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Tag implements IOpenApiObject
{

	/** @var string */
	private $name;

	/** @var string|null */
	private $description;

	/** @var ExternalDocumentation|null */
	private $externalDocs;

	public function __construct(string $name)
	{
		$this->name = $name;
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
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'name' => $this->name,
			'description' => $this->description,
			'externalDocs' => Utils::fromNullable($this->externalDocs),
		]);
	}

}
