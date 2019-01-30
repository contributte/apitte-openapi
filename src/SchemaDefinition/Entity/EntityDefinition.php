<?php declare(strict_types = 1);

namespace Apitte\OpenApi\SchemaDefinition\Entity;

use Apitte\Core\Schema\Endpoint;
use Apitte\Core\Schema\EndpointRequest;
use Apitte\Core\Schema\EndpointResponse;
use Apitte\Core\Schema\Schema as ApiSchema;
use Apitte\OpenApi\SchemaDefinition\IDefinition;

class EntityDefinition implements IDefinition
{

	/** @var ApiSchema */
	protected $schema;

	/** @var IEntityAdapter */
	private $entityAdapter;

	public function __construct(ApiSchema $schema, IEntityAdapter $entityAdapter)
	{
		$this->schema = $schema;
		$this->entityAdapter = $entityAdapter;
	}

	/**
	 * @return mixed[]
	 */
	public function load(): array
	{
		return ['paths' => $this->createPaths()];
	}

	/**
	 * @return mixed[]
	 */
	protected function createPaths(): array
	{
		$paths = [];
		foreach ($this->getEndpoints() as $endpoint) {
			$operations = [];
			foreach ($endpoint->getMethods() as $method) {
				$operations[strtolower($method)] = $this->createOperation($endpoint);
			}
			$paths[$endpoint->getMask() ?? ''] = $operations;
		}
		return $paths;
	}

	/**
	 * @return mixed[]
	 */
	protected function createOperation(Endpoint $endpoint): array
	{
		$operation = [
			'responses' => [],
		];

		// RequestBody
		$request = $endpoint->getRequest();
		if ($request !== null) {
			$operation['requestBody'] = $this->createRequestBody($request);
		}

		// Responses
		foreach ($endpoint->getResponses() as $response) {
			$operation['responses'][$response->getCode()] = $this->createResponse($response);
		}

		return $operation;
	}

	/**
	 * @return mixed[]
	 */
	protected function createRequestBody(EndpointRequest $request): array
	{
		$requestData = ['content' => []];
		if ($request->getEntity() !== null) {
			$requestData['content'] = [
				'application/json' =>
					[
						// TODO resolve types
						'schema' => $this->entityAdapter->getMetadata($request->getEntity()),
					],
			];
		}
		return $requestData;
	}

	/**
	 * @return mixed[]
	 */
	protected function createResponse(EndpointResponse $response): array
	{
		$responseData = [];
		if ($response->getEntity() !== null) {
			$responseData['content'] = [
				'application/json' =>
					[
						// TODO resolve types
						'schema' => $this->entityAdapter->getMetadata($response->getEntity()),
					],
			];
		}
		return $responseData;
	}

	/**
	 * @return Endpoint[]
	 */
	protected function getEndpoints(): array
	{
		return $this->schema->getEndpoints();
	}

}
