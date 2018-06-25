<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Info implements IOpenApiObject
{

	/** @var string */
	private $title;

	/** @var string|null */
	private $description;

	/** @var string|null */
	private $termsOfService;

	/** @var Contact|null */
	private $contact;

	/** @var License|null */
	private $license;

	/** @var string */
	private $version;

	public function __construct(
		string $title,
		?string $description = null,
		?string $termsOfService = null,
		?Contact $contact = null,
		?License $license = null,
		string $version
	)
	{
		$this->title = $title;
		$this->description = $description;
		$this->termsOfService = $termsOfService;
		$this->contact = $contact;
		$this->license = $license;
		$this->version = $version;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'title' => $this->title,
			'description' => $this->description,
			'termsOfService' => $this->termsOfService,
			'contact' => Utils::fromNullable($this->contact),
			'license' => Utils::fromNullable($this->license),
			'version' => $this->version,
		]);
	}

}
