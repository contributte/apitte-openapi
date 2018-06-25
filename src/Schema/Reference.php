<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Reference implements IOpenApiObject
{

	/** @var string */
	private $ref;

	public function __construct(string $ref)
	{
		$this->ref = $ref;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'$ref' => $this->ref,
		]);
	}

}
