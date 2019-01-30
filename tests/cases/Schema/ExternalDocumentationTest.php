<?php declare(strict_types = 1);

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\OpenApi\Schema\ExternalDocumentation;
use Tester\Assert;
use Tester\TestCase;

class ExternalDocumentationTest extends TestCase
{

	public function testOptional(): void
	{
		$description = 'Find more info here';
		$url = 'https://example.com';

		$documentation = new ExternalDocumentation($url);
		$documentation->setDescription($description);

		Assert::same($url, $documentation->getUrl());
		Assert::same($description, $documentation->getDescription());

		// fromArray
		$documentation = ExternalDocumentation::fromArray([
			'url' => $url,
			'description' => $description,
		]);

		Assert::same($url, $documentation->getUrl());
		Assert::same($description, $documentation->getDescription());

		// toArray
		Assert::same([
			'url' => $url,
			'description' => $description,
		], $documentation->toArray());
	}

	public function testRequired(): void
	{
		$url = 'https://example.com';

		$documentation = new ExternalDocumentation($url);

		Assert::same($url, $documentation->getUrl());
		Assert::null($documentation->getDescription());

		// fromArray
		$documentation = ExternalDocumentation::fromArray([
			'url' => $url,
		]);

		Assert::same($url, $documentation->getUrl());
		Assert::null($documentation->getDescription());

		// toArray
		Assert::same([
			'url' => $url,
		], $documentation->toArray());
	}

}

(new ExternalDocumentationTest())->run();
