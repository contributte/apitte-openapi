<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

interface IOpenApiObject
{

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;

}
