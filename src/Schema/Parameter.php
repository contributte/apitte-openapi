<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

use Apitte\Core\Exception\Logical\InvalidArgumentException;

class Parameter
{

	public const
		IN_COOKIE = 'cookie',
		IN_HEADER = 'header',
		IN_PATH = 'path',
		IN_QUERY = 'query';

	public const INS = [
		self::IN_COOKIE,
		self::IN_HEADER,
		self::IN_PATH,
		self::IN_QUERY,
	];

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

	/** @var mixed|null */
	private $example;

	/** @var mixed */
	private $style;

	public function __construct(string $name, string $in)
	{
		if (!in_array($in, self::INS, true)) {
			throw new InvalidArgumentException(sprintf(
				'Invalid value "%s" for attribute "in" given. It must be one of "%s".',
				$in,
				implode(', ', self::INS)
			));
		}

		$this->name = $name;
		$this->in = $in;
	}

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): Parameter
	{
		$parameter = new Parameter($data['name'], $data['in']);
		$parameter->setDescription($data['description'] ?? null);
		$parameter->setRequired($data['required'] ?? false);
		$parameter->setSchema(isset($data['schema']) ? Schema::fromArray($data['schema']) : null);
		$parameter->setExample($data['example'] ?? null);
		$parameter->setStyle($data['style'] ?? null);
		return $parameter;
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
	 * @param mixed|null $example
	 */
	public function setExample($example): void
	{
		$this->example = $example;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		$data['name'] = $this->name;
		$data['in'] = $this->in;
		if ($this->description !== null) {
			$data['description'] = $this->description;
		}
		$data['required'] = $this->required;
		if ($this->schema !== null) {
			$data['schema'] = $this->schema->toArray();
		}
		if ($this->example !== null) {
			$data['example'] = $this->example;
		}
		if ($this->style !== null) {
			$data['style'] = $this->style;
		}
		return $data;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getIn(): string
	{
		return $this->in;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function isRequired(): bool
	{
		return $this->required;
	}

	public function isDeprecated(): bool
	{
		return $this->deprecated;
	}

	public function isAllowEmptyValue(): bool
	{
		return $this->allowEmptyValue;
	}

	/**
	 * @return Reference|Schema|null
	 */
	public function getSchema()
	{
		return $this->schema;
	}

	/**
	 * @return mixed|null
	 */
	public function getExample()
	{
		return $this->example;
	}

	/**
	 * @return mixed
	 */
	public function getStyle()
	{
		return $this->style;
	}

	/**
	 * @param mixed $style
	 */
	public function setStyle($style): void
	{
		$this->style = $style;
	}

}
