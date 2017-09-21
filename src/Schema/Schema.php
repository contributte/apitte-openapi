<?php

namespace Apitte\OpenApi\Schema;

class Schema implements IOpenApiObject
{

	/** @var mixed[] */
	private $data = [];

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::create($this->data);
	}

}
