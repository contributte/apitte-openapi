<?php declare(strict_types = 1);

namespace Apitte\OpenApi;

use Apitte\Core\Schema\Endpoint;
use Apitte\Core\Schema\Schema as ApiSchema;
use Apitte\OpenApi\Schema\Info;
use Apitte\OpenApi\Schema\MediaType;
use Apitte\OpenApi\Schema\OpenApi;
use Apitte\OpenApi\Schema\Operation;
use Apitte\OpenApi\Schema\Parameter;
use Apitte\OpenApi\Schema\PathItem;
use Apitte\OpenApi\Schema\Paths;
use Apitte\OpenApi\Schema\RequestBody;
use Apitte\OpenApi\Schema\Response;
use Apitte\OpenApi\Schema\Responses;
use Apitte\OpenApi\Schema\Schema;
use Apitte\OpenApi\Schema\Tag;
use Apitte\OpenApi\SchemaType\ISchemaType;

class OpenApiService
{

	/** @var ApiSchema */
	protected $schema;

	/** @var Info */
	protected $apiInfo;

	/** @var ISchemaType */
	private $schemaType;

	public function __construct(
		ApiSchema $schema,
		Info $apiInfo,
		ISchemaType $schemaType
	)
	{
		$this->schema = $schema;
		$this->apiInfo = $apiInfo;
		$this->schemaType = $schemaType;
	}

	public function createSchema(): OpenApi
	{
		$paths = new Paths();
		$openApi = new OpenApi($this->apiInfo, $paths);

		$endpointId = 0;
		foreach ($this->getEndpoints() as $endpoint) {
			$endpointId++;

			$pathItem = $paths->getPath((string) $endpoint->getMask());
			if ($pathItem === null) {
				$pathItem = new PathItem();
			}

			foreach ($endpoint->getMethods() as $method) {

				//Request MediaType
				$mediaType = new MediaType();
				$body = new Schema([
					'type' => 'object',
					//					'required' => [
					//						'name',
					//					],
					//					'properties' => (object) [
					//						'name' => (object) [
					//							'type' => 'string',
					//						],
					//						'age' => (object) [
					//							'type' => 'integer',
					//							'format' => 'int32',
					//							'minimum' => 0,
					//						],
					//					],
				]);
				$mediaType->setSchema($body);

				//Request body
				$requestBody = new RequestBody(['application/json' => $mediaType]);
				$requestBody->setDescription('Request body description');
				$requestBody->setRequired(true);

				//Reference
				//$schemaReference = new ComponentReference(
				//	ComponentReference::TYPE_SCHEMA,
				//	'sample'
				//);

				//Response MediaType
				$mediaType = new MediaType();
				$mediaType->setSchema($body);

				//Default response - required
				$defaultResponse = new Response('Response description');
				$defaultResponse->setContent('application/json', $mediaType);

				//Responses
				$responses = new Responses($defaultResponse);

				//Create unique operation id
				$operationId = $endpointId . $method;

				//Operation
				$operation = new Operation($operationId, $responses);
				$operation->setTags($this->getOperationTags($endpoint));
				$description = (string) $endpoint->getDescription();

				$lines = explode("\n", $description);

				$operation->setSummary(array_shift($lines));
				if (count($lines) > 0) {
					$operation->setDescription(implode('<br>', $lines));
				}
				$operation->setDeprecated(false);

				//Parameters
				foreach ($endpoint->getParameters() as $endpointParam) {
					$param = new Parameter($endpointParam->getName(), $endpointParam->getIn());
					$param->setDescription($endpointParam->getDescription());
					$param->setRequired($endpointParam->isRequired());
					$param->setAllowEmptyValue($endpointParam->isAllowEmpty());
					$param->setDeprecated($endpointParam->isDeprecated());
					$param->setSchema($this->schemaType->createSchema($endpointParam));

					$operation->setParameter($param);
				}
				$method = strtolower($method);
				if ($method === PathItem::OPERATION_PUT || $method === PathItem::OPERATION_POST || $method === PathItem::OPERATION_PATCH) {
					$operation->setRequestBody($requestBody);
				}
				$pathItem->setOperation($method, $operation);
			}
			$paths->setPathItem((string) $endpoint->getMask(), $pathItem);
		}

		//Global tags
		foreach ($this->getTags() as $tag) {
			$openApi->addTag($tag);
		}

		//Components
		//$components = new Components();
		//$components->setSchema('sample', $schema);
		//$openApi->setComponents($components);

		return $openApi;
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

	/**
	 * @return Tag[]
	 */
	protected function getTags(): array
	{
		return [];
	}

}
