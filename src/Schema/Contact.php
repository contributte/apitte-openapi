<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Contact implements IOpenApiObject
{

	/** @var string|null */
	private $name;

	/** @var string|null */
	private $url;

	/** @var string|null */
	private $email;

	public function __construct(?string $name, ?string $url, ?string $email)
	{
		$this->name = $name;
		$this->url = $url;
		$this->email = $email;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'name' => $this->name,
			'url' => $this->url,
			'email' => $this->email,
		]);
	}

}
