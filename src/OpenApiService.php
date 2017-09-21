<?php

namespace Apitte\OpenApi;

use Apitte\Core\Schema\ApiSchema;
use Apitte\OpenApi\Schema\Components;
use Apitte\OpenApi\Schema\Extended\ComponentReference;
use Apitte\OpenApi\Schema\ExternalDocumentation;
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
use Apitte\OpenApi\Schema\Server;
use Apitte\OpenApi\Schema\Tag;

class OpenApiService
{

	/** @var ApiSchema */
	private $schema;

	/**
	 * @param ApiSchema $schema
	 */
	public function __construct(ApiSchema $schema)
	{
		$this->schema = $schema;
	}

	/**
	 * @return OpenApi
	 */
	public function createSchema()
	{
		$info = new Info('Api Docs', '2.0.5-beta');
		$paths = new Paths();
		$openApi = new OpenApi($info, $paths);

		$endpointId = 0;
		foreach ($this->schema->getEndpoints() as $endpoint) {
			$endpointId++;

			$pathItem = new PathItem();
			foreach ($endpoint->getMethods() as $method) {

				//Parameter
				$param = new Parameter('id', Parameter::IN_QUERY);
				$param->setDescription('Popis parametru');
				$param->setRequired(TRUE);
				$param->setAllowEmptyValue(FALSE);
				$param->setDeprecated(FALSE);
				$param->setSchema(new Schema([
					'type' => 'integer',
					'format' => 'int32',
				]));

				//Request MediaType
				$mediaType = new MediaType();
				$mediaType->setSchema(new Schema([
					'type' => 'object',
					'required' => [
						'name',
					],
					'properties' => (object) [
						'name' => (object) [
							'type' => 'string',
						],
						'age' => (object) [
							'type' => 'integer',
							'format' => 'int32',
							'minimum' => 0,
						],
					],
				]));

				//Request body
				$requestBody = new RequestBody(['application/json' => $mediaType]);
				$requestBody->setDescription('Popis request body');
				$requestBody->setRequired(TRUE);

				//Reference
				$schemaReference = new ComponentReference(
					ComponentReference::TYPE_SCHEMA,
					'sample'
				);

				//Response MediaType
				$mediaType = new MediaType();
				$mediaType->setSchema($schemaReference);

				//Default response - required
				$defaultResponse = new Response('Popis odpovědi');
				$defaultResponse->setContent('application/json', $mediaType);

				//Responses
				$responses = new Responses($defaultResponse);

				//Create unique operation id
				$operationId = $endpointId . $method;

				//Operation
				$operation = new Operation($operationId, $responses);
				$operation->setTags(['prvniTag', 'druhyTag']);
				$operation->setDescription('Dlouhý popis operace');
				$operation->setSummary('Krátky popis oberace');
				$operation->setDeprecated(FALSE);
				$operation->setParameter($param);
				$operation->setRequestBody($requestBody);

				$pathItem->setOperation(strtolower($method), $operation);
			}
			$paths->setPathItem($endpoint->getMask(), $pathItem);
		}

		//Schema model
		$schema = new Schema([
			'type' => 'object',
			'required' => [
				'name',
			],
			'properties' => (object) [
				'name' => (object) [
					'type' => 'string',
				],
				'age' => (object) [
					'type' => 'integer',
					'format' => 'int32',
					'minimum' => 0,
				],
			],
		]);

		//Components
		$components = new Components();
		$components->setSchema('sample', $schema);
		$openApi->setComponents($components);

		//Server
		$server = new Server('http://localhost/ispa/applications/sample-project/www/apiv2');
		$server->setDescription('Localhost');
		$openApi->addServer($server);

		//Tag description
		$tag = new Tag('prvniTag');
		$tag->setDescription('Jednoduchý popisek prvního tagu');
		$openApi->addTag($tag);

		//External docs
		$docs = new ExternalDocumentation('http:\\www.google.com');
		$docs->setDescription('Dokumentace druhého tagu');

		//Tag description with external docs
		$tag = new Tag('druhyTag');
		$tag->setDescription('Popisek druhého tagu');
		$tag->setExternalDocs($docs);
		$openApi->addTag($tag);

		return $openApi;
	}

}
