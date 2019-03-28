<?php declare(strict_types = 1);

/**
 * Test: SchemaDefinition\CoreSchemaDefinition
 */

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\Core\Schema\Endpoint;
use Apitte\Core\Schema\EndpointHandler;
use Apitte\Core\Schema\EndpointParameter;
use Apitte\Core\Schema\EndpointResponse;
use Apitte\Core\Schema\Schema;
use Apitte\OpenApi\SchemaDefinition\CoreDefinition;
use Tester\Assert;
use Tester\TestCase;

final class CoreDefinitionTest extends TestCase
{

	public function testString(): void
	{
		$schema = new Schema();

		$endpoint = new Endpoint(new EndpointHandler('class', 'method'));
		$endpoint->setMask('/foo/bar');
		$endpoint->setMethods(['GET']);
		$endpoint->addTag('tag1');
		$schema->addEndpoint($endpoint);

		$endpoint = new Endpoint(new EndpointHandler('class', 'method'));

		$endpoint->setMask('/foo/bar');
		$endpoint->setMethods(['POST', 'PUT']);
		$endpoint->setDescription('description');
		$endpoint->addTag('tag2');
		$endpoint->addTag('tag3', 'value3');

		$endpoint->setOpenApi([
			'controller' => [
				'info' => [
					'title' => 'Title',
					'version' => '1.0.0',
				],
			],
			'method' => [
				'description' => 'Not overridden description',
			],
		]);

		$response = new EndpointResponse('200', 'description');
		$response->setEntity('class');
		$endpoint->addResponse($response);

		$parameter = new EndpointParameter('parameter1');
		$parameter->setDescription('description');
		$endpoint->addParameter($parameter);

		$parameter = new EndpointParameter('parameter2');
		$parameter->setDescription('description');
		$parameter->setRequired(false);
		$parameter->setAllowEmpty(true);
		$parameter->setDeprecated(true);
		$parameter->setIn('query');
		$endpoint->addParameter($parameter);

		$schema->addEndpoint($endpoint);

		$definition = new CoreDefinition($schema);

		Assert::same(
			[
				'info' => ['title' => 'Title', 'version' => '1.0.0'],
				'paths' => [
					'/foo/bar' => [
						'get' => ['responses' => [], 'tags' => ['tag1']],
						'post' => [
							'description' => 'description',
							'responses' => [200 => ['description' => 'description']],
							'tags' => ['tag2', 'tag3'],
							'parameters' => [
								[
									'name' => 'parameter1',
									'in' => 'path',
									'description' => 'description',
									'required' => true,
									'schema' => ['type' => 'string'],
								],
								[
									'name' => 'parameter2',
									'in' => 'query',
									'description' => 'description',
									'required' => false,
									'schema' => ['type' => 'string'],
								],
							],
						],
						'put' => [
							'description' => 'description',
							'responses' => [200 => ['description' => 'description']],
							'tags' => ['tag2', 'tag3'],
							'parameters' => [
								[
									'name' => 'parameter1',
									'in' => 'path',
									'description' => 'description',
									'required' => true,
									'schema' => ['type' => 'string'],
								],
								[
									'name' => 'parameter2',
									'in' => 'query',
									'description' => 'description',
									'required' => false,
									'schema' => ['type' => 'string'],
								],
							],
						],
					],
				],
			],
			$definition->load()
		);
	}

}

(new CoreDefinitionTest())->run();
