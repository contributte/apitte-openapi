<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Parameter implements IOpenApiObject
{

	public const
		IN_QUERY = 'query',
		IN_COOKIE = 'cookie',
		IN_HEADER = 'header',
		IN_PATH = 'path';

	/** @var string */
	private $name;

	/** @var string */
	private $in;

	/** @var string|null */
	private $description;

	/** @var bool */
	private $required = false;

	/** @var bool */
	private $deprecated = false;

	/** @var bool */
	private $allowEmptyValue = false;

	/** @var Schema|Reference|null */
	private $schema;

	public function __construct(string $name, string $in)
	{
		$this->name = $name;
		$this->in = $in;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function setRequired(bool $required): void
	{
		$this->required = $required;
	}

	public function setDeprecated(bool $deprecated): void
	{
		$this->deprecated = $deprecated;
	}

	public function setAllowEmptyValue(bool $allowEmptyValue): void
	{
		$this->allowEmptyValue = $allowEmptyValue;
	}

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
