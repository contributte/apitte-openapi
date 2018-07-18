<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Server implements IOpenApiObject
{

	/** @var string */
	private $url;

	/** @var string|null */
	private $description;

	/** @var ServerVariable[] */
	private $variables;

	public function __construct(string $url)
	{
		$this->url = $url;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'url' => $this->url,
			'description' => $this->description,
		]);
	}

}
