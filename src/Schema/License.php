<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class License implements IOpenApiObject
{

	/** @var string */
	private $name;

	/** @var string|null */
	private $url;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'name' => $this->name,
			'url' => $this->url,
		]);
	}

}
