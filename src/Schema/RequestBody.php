<?php

namespace Apitte\OpenApi\Schema;

class RequestBody implements IOpenApiObject
{

	/** @var string|NULL */
	private $description;

	/** @var MediaType[] */
	private $content = [];

	/** @var bool */
	private $required = FALSE;

	/**
	 * @param MediaType[] $content
	 */
	public function __construct(array $content)
	{
		$this->content = $content;
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
	 * @param bool $required
	 * @return void
	 */
	public function setRequired($required)
	{
		$this->required = $required;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'description' => $this->description,
			'content' => Utils::fromArray($this->content),
			'required' => $this->required,
		]);
	}

}
