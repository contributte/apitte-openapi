<?php

namespace Apitte\OpenApi\Schema;

class ExternalDocumentation implements IOpenApiObject
{

	/** @var string|NULL */
	private $description;

	/** @var string */
	private $url;

	/**
	 * @param string $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
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
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'description' => $this->description,
			'url' => $this->url,
		]);
	}

}
