<?php

namespace Apitte\OpenApi\Schema;

class Reference implements IOpenApiObject
{

	/** @var string */
	private $ref;

	/**
	 * @param string $ref
	 */
	public function __construct($ref)
	{
		$this->ref = $ref;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create([
			'$ref' => $this->ref,
		]);
	}

}
