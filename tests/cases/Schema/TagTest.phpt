<?php declare(strict_types = 1);

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\OpenApi\Schema\Tag;
use Tester\Assert;
use Tester\TestCase;

class TagTest extends TestCase
{

	public function testOptional(): void
	{
		$name = 'pet';
		$description = 'Pets operations';

		$tag = new Tag($name);
		$tag->setDescription($description);

		Assert::same($name, $tag->getName());
		Assert::same($description, $tag->getDescription());

		// fromArray
		$tag = Tag::fromArray([
			'name' => $name,
			'description' => $description,
		]);

		Assert::same($name, $tag->getName());
		Assert::same($description, $tag->getDescription());

		// toArray
		Assert::same([
			'name' => $name,
			'description' => $description,
		], $tag->toArray());
	}

	public function testRequired(): void
	{
		$name = 'pet';

		$tag = new Tag($name);

		Assert::same($name, $tag->getName());
		Assert::null($tag->getDescription());
		Assert::null($tag->getExternalDocs());

		// fromArray
		$tag = Tag::fromArray([
			'name' => $name,
		]);

		Assert::same($name, $tag->getName());
		Assert::null($tag->getDescription());
		Assert::null($tag->getExternalDocs());

		// toArray
		Assert::same([
			'name' => $name,
		], $tag->toArray());
	}

}

(new TagTest())->run();
