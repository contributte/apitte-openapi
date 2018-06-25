<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class ServerVariable implements IOpenApiObject
{

	/** @var string[] */
	private $enum;

	/** @var string */
	private $default;

	/** @var string */
	private $description;

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'enum' => $this->enum,
			'default' => $this->default,
			'description' => $this->description,
		];
	}

}
