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
