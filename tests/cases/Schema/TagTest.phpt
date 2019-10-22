<?php declare(strict_types = 1);

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\OpenApi\Schema\Tag;
use Tester\Assert;
use Tester\TestCase;

class TagTest extends TestCase
{

	public function testOptional(): void
	{
		$tag = new Tag('pet');
		$tag->setDescription('Pets operations');

		Assert::same('pet', $tag->getName());
		Assert::same('Pets operations', $tag->getDescription());

		$realData = $tag->toArray();
		$expectedData = ['name' => 'pet', 'description' => 'Pets operations'];

		Assert::same($expectedData, $realData);
		Assert::same($expectedData, Tag::fromArray($realData)->toArray());
	}

	public function testRequired(): void
	{
		$tag = new Tag('pet');

		Assert::same('pet', $tag->getName());
		Assert::null($tag->getDescription());
		Assert::null($tag->getExternalDocs());

		$realData = $tag->toArray();
		$expectedData = ['name' => 'pet'];

		Assert::same($expectedData, $realData);
		Assert::same($expectedData, Tag::fromArray($realData)->toArray());
	}

}

(new TagTest())->run();
