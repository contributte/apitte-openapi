<?php

namespace Apitte\OpenApi\Schema;

class Server implements IOpenApiObject
{

	/** @var string */
	private $url;

	/** @var string|NULL */
	private $description;

	/* Map[string, Server Variable Object]*/
	private $variables;

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
			'url' => $this->url,
			'description' => $this->description,
		]);
	}

}
