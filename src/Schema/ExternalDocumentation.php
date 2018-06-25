<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class ExternalDocumentation implements IOpenApiObject
{

	/** @var string|null */
	private $description;

	/** @var string */
	private $url;

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
			'description' => $this->description,
			'url' => $this->url,
		]);
	}

}
