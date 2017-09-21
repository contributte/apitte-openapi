<?php

namespace Apitte\OpenApi\Schema;

class License implements IOpenApiObject
{

	/** @var string */
	private $name;

	/** @var string|NULL */
	private $url;

	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'name' => $this->name,
			'url' => $this->url,
		]);
	}

}
