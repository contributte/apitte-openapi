<?php

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

	/**
	 * @param string $description
	 */
	public function __construct($description)
	{
		$this->description = $description;
	}

	/**
	 * @param string $type
	 * @param MediaType $content
	 * @return void
	 */
	public function setContent($type, MediaType $content)
	{
		$this->content[$type] = $content;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'description' => $this->description,
			'content' => Utils::fromArray($this->content),
		]);
	}

}
