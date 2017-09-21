<?php

namespace Apitte\OpenApi\Schema;

class Tag implements IOpenApiObject
{

	/** @var string */
	private $name;

	/** @var string|NULL */
	private $description;

	/** @var ExternalDocumentation|NULL */
	private $externalDocs;

	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
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
	 * @param ExternalDocumentation|NULL $externalDocs
	 * @return void
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
	}

	/**
	 * @return mixed
	 */
	public function toArray()
	{
		return Utils::create([
			'name' => $this->name,
			'description' => $this->description,
			'externalDocs' => Utils::fromNullable($this->externalDocs),
		]);
	}

}
