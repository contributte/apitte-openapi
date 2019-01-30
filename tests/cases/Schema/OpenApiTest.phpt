<?php declare(strict_types = 1);

/**
 * Test: SchemaType\BaseSchemaType
 */

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\OpenApi\Schema\OpenApi;
use Symfony\Component\Yaml\Yaml;
use Tester\Assert;
use Tester\TestCase;

final class OpenApiTest extends TestCase
{

	public function testApiWithExamples(): void
	{
		// TODO parse json in yaml?
		// $neonData = Yaml::parseFile(__DIR__ . '/examples/api-with-examples.yaml');
		// $openApi = OpenApi::fromArray($neonData);
		// $data = $openApi->toArray();
		// self::assertSameDataStructure($neonData, $data);
	}

	public function testCallbackExample(): void
	{
		// TODO callbacks
		//$neonData = Yaml::parseFile(__DIR__ . '/examples/callback-example.yaml');
		//$openApi = OpenApi::fromArray($neonData);
		//$data = $openApi->toArray();
		//self::assertSameDataStructure($neonData, $data);
	}

	public function testLinkExample(): void
	{
		// TODO links
		//$neonData = Yaml::parseFile(__DIR__ . '/examples/link-example.yaml');
		//$openApi = OpenApi::fromArray($neonData);
		//$data = $openApi->toArray();
		//self::assertSameDataStructure($neonData, $data);
	}

	public function testPetstore(): void
	{
		$neonData = Yaml::parseFile(__DIR__ . '/examples/petstore.yaml');
		$openApi = OpenApi::fromArray($neonData);
		$data = $openApi->toArray();
		self::assertSameDataStructure($neonData, $data);
	}

	public function testPetstoreExpanded(): void
	{
		$neonData = Yaml::parseFile(__DIR__ . '/examples/petstore-expanded.yaml');
		$openApi = OpenApi::fromArray($neonData);
		$data = $openApi->toArray();
		self::assertSameDataStructure($neonData, $data);
	}

	public function testUspto(): void
	{
		$neonData = Yaml::parseFile(__DIR__ . '/examples/uspto.yaml');
		$openApi = OpenApi::fromArray($neonData);
		$data = $openApi->toArray();
		self::assertSameDataStructure($neonData, $data);
	}

	/**
	 * @param mixed[] $expected
	 * @param mixed[] $actual
	 */
	private static function assertSameDataStructure(array $expected, array $actual): void
	{
		self::recursiveSort($expected);
		self::recursiveSort($actual);
		Assert::same($expected, $actual);
	}

	/**
	 * @param mixed[] $data
	 */
	private static function recursiveSort(array &$data): void
	{
		foreach ($data as &$value) {
			if (is_array($value)) {
				self::recursiveSort($value);
			}
		}
		ksort($data);
	}

}

(new OpenApiTest())->run();
