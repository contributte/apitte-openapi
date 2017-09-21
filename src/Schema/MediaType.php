<?php

namespace Apitte\OpenApi\Schema;

class MediaType implements IOpenApiObject
{

	/** @var Schema|Reference|NULL */
	private $schema;

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
			'schema' => Utils::fromNullable($this->schema),
		]);
	}

}
