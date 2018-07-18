<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class RequestBody implements IOpenApiObject
{

	/** @var string|null */
	private $description;

	/** @var MediaType[] */
	private $content = [];

	/** @var bool */
	private $required = false;

	/**
	 * @param MediaType[] $content
	 */
	public function __construct(array $content)
	{
		$this->content = $content;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function setRequired(bool $required): void
	{
		$this->required = $required;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'description' => $this->description,
			'content' => Utils::fromArray($this->content),
			'required' => $this->required,
		]);
	}

}
