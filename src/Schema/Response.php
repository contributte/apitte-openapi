<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Response implements IOpenApiObject
{

	/** @var string */
	private $description;

	/** @var Header[]|Reference[] */
	private $headers = [];

	/** @var MediaType[] */
	private $content = [];

	/** @var Link[]|Reference[] */
	private $links = [];

	public function __construct(string $description)
	{
		$this->description = $description;
	}

	public function setContent(string $type, MediaType $content): void
	{
		$this->content[$type] = $content;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'description' => $this->description,
			'content' => Utils::fromArray($this->content),
		]);
	}

}
