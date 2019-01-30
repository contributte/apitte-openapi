<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class MediaType
{

	/** @var Schema|Reference|null */
	private $schema;

	/** @var mixed[]|null */
	private $example;

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): MediaType
	{
		$mediaType = new MediaType();
		$mediaType->setSchema(isset($data['schema']) ? Schema::fromArray($data['schema']) : null);
		$mediaType->setExample($data['example'] ?? null);
		return $mediaType;
	}

	/**
	 * @param Schema|Reference|null $schema
	 */
	public function setSchema($schema): void
	{
		$this->schema = $schema;
	}

	/**
	 * @return mixed[]|null
	 */
	public function getExample(): ?array
	{
		return $this->example;
	}

	/**
	 * @param mixed[]|null $example
	 */
	public function setExample(?array $example): void
	{
		$this->example = $example;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		if ($this->schema !== null) {
			$data['schema'] = $this->schema->toArray();
		}
		if ($this->example !== null) {
			$data['example'] = $this->example;
		}
		return $data;
	}

}
