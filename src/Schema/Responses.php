<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Responses
{

	/** @var Response[]|Reference[] */
	private $responses = [];

	public function setResponse(string $key, Response $response): void
	{
		$this->responses[$key] = $response;
	}

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): Responses
	{
		$responses = new Responses();
		foreach ($data as $key => $responseData) {
			$responses->setResponse((string) $key, Response::fromArray($responseData));
		}
		return $responses;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		foreach ($this->responses as $key => $response) {
			if ($key !== 'default') {
				$data[$key] = $response->toArray();
			}
		}

		// Default response last
		if (isset($this->responses['default'])) {
			$data['default'] = $this->responses['default']->toArray();
		}

		return $data;
	}

}
