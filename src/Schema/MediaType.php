<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class MediaType implements IOpenApiObject
{

	/** @var Schema|Reference|null */
	private $schema;

	/**
	 * @param Schema|Reference|null $schema
	 */
	public function setSchema($schema): void
	{
		$this->schema = $schema;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'schema' => Utils::fromNullable($this->schema),
		]);
	}

}
