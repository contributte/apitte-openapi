<?php

namespace Apitte\OpenApi\Schema;

class Contact implements IOpenApiObject
{

	/** @var string|NULL */
	private $name;

	/** @var string|NULL */
	private $url;

	/** @var string|NULL */
	private $email;

	/**
	 * @param string|null $name
	 * @param string|null $url
	 * @param string|null $email
	 */
	public function __construct($name, $url, $email)
	{
		$this->name = $name;
		$this->url = $url;
		$this->email = $email;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'name' => $this->name,
			'url' => $this->url,
			'email' => $this->email,
		]);
	}

}
