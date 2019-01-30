<?php declare(strict_types = 1);

namespace Apitte\OpenApi\SchemaDefinition;

use Apitte\Core\Schema\Endpoint;
use Apitte\Core\Schema\Schema as ApiSchema;
use Apitte\OpenApi\Utils\Helpers;

class AnnotationDefinition implements IDefinition
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
			$operations = [];
			foreach ($endpoint->getMethods() as $method) {
				$operations[strtolower($method)] = $this->createOperation($endpoint);
			}
			$data['paths'][(string) $endpoint->getMask()] = $operations;
			$data = Helpers::merge($data, $endpoint->getOpenApi()['controller']);
		}
		return $data;
	}

	/**
	 * @return mixed[]
	 */
	protected function createOperation(Endpoint $endpoint): array
	{
		return $endpoint->getOpenApi()['method'];
	}

	/**
	 * @return Endpoint[]
	 */
	protected function getEndpoints(): array
	{
		return $this->schema->getEndpoints();
	}

}
