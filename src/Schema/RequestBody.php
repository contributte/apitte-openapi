<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class RequestBody
{

	/** @var string|null */
	private $description;

	/** @var MediaType[] */
	private $content = [];

	/** @var bool */
	private $required = false;

	/**
	 * @param MediaType[] $content
	 */
	public function __construct(array $content)
	{
		$this->content = $content;
	}

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): RequestBody
	{
		$requestBody = new RequestBody($data['content']);
		$requestBody->setRequired($data['required'] ?? false);
		$requestBody->setDescription($data['description'] ?? null);
		return $requestBody;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		if ($this->description !== null) {
			$data['description'] = $this->description;
		}
		$data['content'] = $this->content;
		if ($this->required === true) {
			$data['required'] = true;
		}
		return $data;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function setRequired(bool $required): void
	{
		$this->required = $required;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	/**
	 * @return MediaType[]
	 */
	public function getContent(): array
	{
		return $this->content;
	}

	public function isRequired(): bool
	{
		return $this->required;
	}

}
