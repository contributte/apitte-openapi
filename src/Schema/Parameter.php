<?php

namespace Apitte\OpenApi\Schema;

class Parameter implements IOpenApiObject
{

	const IN_QUERY = 'query';
	const IN_COOKIE = 'cookie';
	const IN_HEADER = 'header';
	const IN_PATH = 'path';

	/** @var string */
	private $name;

	/** @var string */
	private $in;

	/** @var string|NULL */
	private $description;

	/** @var bool */
	private $required = FALSE;

	/** @var bool */
	private $deprecated = FALSE;

	/** @var bool */
	private $allowEmptyValue = FALSE;

	/** @var Schema|Reference|NULL */
	private $schema;

	/**
	 * @param string $name
	 * @param string $in
	 */
	public function __construct($name, $in)
	{
		$this->name = $name;
		$this->in = $in;
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
	 * @param bool $deprecated
	 * @return void
	 */
	public function setDeprecated($deprecated)
	{
		$this->deprecated = $deprecated;
	}

	/**
	 * @param bool $allowEmptyValue
	 * @return void
	 */
	public function setAllowEmptyValue($allowEmptyValue)
	{
		$this->allowEmptyValue = $allowEmptyValue;
	}

	/**
	 * @param Schema|Reference|NULL $schema
	 * @return void
	 */
	public function setSchema($schema)
	{
		$this->schema = $schema;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'name' => $this->name,
			'in' => $this->in,
			'description' => $this->description,
			'required' => $this->required,
			'deprecated' => $this->deprecated,
			'allowEmptyValue' => $this->allowEmptyValue,
			'schema' => Utils::fromNullable($this->schema),
		]);
	}

}
