<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Response
{

	/** @var string */
	private $description;

	/** @var Header[]|Reference[] */
	private $headers = [];

	/** @var MediaType[] */
	private $content = [];

	/** @var Link[]|Reference[] */
	private $links = [];

	public function __construct(string $description)
	{
		$this->description = $description;
	}

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): Response
	{
		$response = new Response($data['description']);
		if (isset($data['headers'])) {
			foreach ($data['headers'] as $key => $headerData) {
				$response->setHeader($key, Header::fromArray($headerData));
			}
		}

		if (isset($data['content'])) {
			foreach ($data['content'] as $key => $contentData) {
				$response->setContent($key, MediaType::fromArray($contentData));
			}
		}

		return $response;
	}

	public function setContent(string $type, MediaType $mediaType): void
	{
		$this->content[$type] = $mediaType;
	}

	public function setHeader(string $key, Header $header): void
	{
		$this->headers[$key] = $header;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = [];
		$data['description'] = $this->description;
		foreach ($this->headers as $key => $header) {
			$data['headers'][$key] = $header->toArray();
		}

		foreach ($this->content as $key => $mediaType) {
			$data['content'][$key] = $mediaType->toArray();
		}

		return $data;
	}

}
