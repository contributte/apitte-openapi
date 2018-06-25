<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Responses implements IOpenApiObject
{

	/** @var Response[]|Reference[] */
	private $responses = [];

	/**
	 * @param Response|Reference $defaultResponse
	 */
	public function __construct($defaultResponse)
	{
		$this->responses['default'] = $defaultResponse;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::fromArray($this->responses);
	}

}
