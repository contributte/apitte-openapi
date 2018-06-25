<?php declare(strict_types = 1);

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
	public function toArray(): array
	{
		return Utils::create($this->data);
	}

}
