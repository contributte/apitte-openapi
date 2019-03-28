<?php declare(strict_types = 1);

namespace Apitte\OpenApi\SchemaDefinition;

use Apitte\Core\Schema\Endpoint;
use Apitte\Core\Schema\EndpointParameter;
use Apitte\Core\Schema\EndpointRequest;
use Apitte\Core\Schema\EndpointResponse;
use Apitte\Core\Schema\Schema as ApiSchema;
use Apitte\OpenApi\Utils\Helpers;

class CoreDefinition implements IDefinition
{

	/** @var ApiSchema */
	protected $schema;

	public function __construct(ApiSchema $schema)
	{
		$this->schema = $schema;
	}

	/**
	 * @return mixed[]
	 */
	public function load(): array
	{
		$data = ['paths' => []];
		foreach ($this->getEndpoints() as $endpoint) {
			foreach ($endpoint->getMethods() as $method) {
				$data['paths'][(string) $endpoint->getMask()][strtolower($method)] = $this->createOperation($endpoint);
			}
			$data = Helpers::merge($data, $endpoint->getOpenApi()['controller'] ?? []);
		}
		return $data;
	}

	/**
	 * @return mixed[]
	 */
	protected function createOperation(Endpoint $endpoint): array
	{
		$operation = [
			'responses' => $this->createResponses($endpoint),
		];

		// Description
		$description = $endpoint->getDescription();
		if ($description !== null) {
			$operation['description'] = $description;
		}

		// Tags
		$tags = $this->getOperationTags($endpoint);
		if (count($tags) > 0) {
			$operation['tags'] = array_keys($tags);
		}

		// Parameters
		foreach ($endpoint->getParameters() as $endpointParam) {
			$operation['parameters'][] = $this->createParameter($endpointParam);
		}

		$request = $endpoint->getRequest();
		if ($request !== null) {
			$operation['requestBody'] = $this->createRequestData($request);
		}

		// TODO summary + description
		// $lines = explode("\n", $description);
		// $operation->setSummary(array_shift($lines));
		// if (count($lines) > 0) {
		// $operation->setDescription(implode('<br>', $lines));

		// TODO deprecated
		// $operation->setDeprecated(false);

		$operation = Helpers::merge($operation, $endpoint->getOpenApi()['method'] ?? []);

		return $operation;
	}

	/**
	 * @return mixed[]
	 */
	protected function createRequestData(EndpointRequest $request): array
	{
		$requestData = ['content' => []];
		if ($request->isRequired() === true) {
			$requestData['required'] = true;
		}
		if ($request->getDescription() !== null) {
			$requestData['description'] = $request->getDescription();
		}
		return $requestData;
	}

	/**
	 * @return mixed[]
	 */
	protected function createResponses(Endpoint $endpoint): array
	{
		$responses = [];
		foreach ($endpoint->getResponses() as $response) {
			$responses[$response->getCode()] = $this->createResponse($response);
		}
		return $responses;
	}

	/**
	 * @return mixed[]
	 */
	protected function createResponse(EndpointResponse $response): array
	{
		return [
			'description' => $response->getDescription(),
		];
	}

	/**
	 * @return mixed[]
	 */
	protected function createParameter(EndpointParameter $endpointParameter): array
	{
		$parameter = [
			'name' => $endpointParameter->getName(),
			'in' => $endpointParameter->getIn(),
		];

		$parameterDescription = $endpointParameter->getDescription();
		if ($parameterDescription !== null) {
			$parameter['description'] = $parameterDescription;
		}
		$parameter['required'] = $endpointParameter->isRequired();
		$parameter['schema'] = ['type' => 'string'];

		// $param->setAllowEmptyValue($endpointParam->isAllowEmpty());
		// $param->setDeprecated($endpointParam->isDeprecated());
		// TODO types should be bool but now are strings
		// TODO schema

		return $parameter;
	}

	/**
	 * @return Endpoint[]
	 */
	protected function getEndpoints(): array
	{
		return $this->schema->getEndpoints();
	}

	/**
	 * @return string[]
	 */
	protected function getOperationTags(Endpoint $endpoint): array
	{
		$tags = $endpoint->getTags();
		unset($tags[Endpoint::TAG_ID]);
		return $tags;
	}

}
