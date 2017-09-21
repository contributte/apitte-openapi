<?php

namespace Apitte\OpenApi\Schema;

class Info implements IOpenApiObject
{

	/** @var string */
	private $title;

	/** @var string|NULL */
	private $description;

	/** @var string|NULL */
	private $termsOfService;

	/** @var Contact|NULL */
	private $contact;

	/** @var License|NULL */
	private $license;

	/** @var string */
	private $version;

	/**
	 * @param string $title
	 * @param string $version
	 */
	public function __construct($title, $version)
	{
		$this->title = $title;
		$this->version = $version;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'title' => $this->title,
			'description' => $this->description,
			'termsOfService' => $this->termsOfService,
			'contact' => Utils::fromNullable($this->contact),
			'license' => Utils::fromNullable($this->license),
			'version' => $this->version,
		]);
	}

}
