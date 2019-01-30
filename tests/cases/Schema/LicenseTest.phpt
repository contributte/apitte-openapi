<?php declare(strict_types = 1);

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\OpenApi\Schema\License;
use Tester\Assert;
use Tester\TestCase;

class LicenseTest extends TestCase
{

	public function testOptional(): void
	{
		$name = 'Apache 2.0';
		$url = 'https://www.apache.org/licenses/LICENSE-2.0.html';

		$license = new License($name);
		$license->setUrl($url);

		Assert::same($name, $license->getName());
		Assert::same($url, $license->getUrl());

		// fromArray
		$license = License::fromArray([
			'name' => $name,
			'url' => $url,
		]);

		Assert::same($name, $license->getName());
		Assert::same($url, $license->getUrl());

		// toArray
		Assert::same([
			'name' => $name,
			'url' => $url,
		], $license->toArray());
	}

	public function testRequired(): void
	{
		$name = 'Apache 2.0';

		$license = new License($name);

		Assert::same($name, $license->getName());
		Assert::null($license->getUrl());

		// fromArray
		$license = License::fromArray([
			'name' => $name,
		]);

		Assert::same($name, $license->getName());
		Assert::null($license->getUrl());

		// toArray
		Assert::same([
			'name' => $name,
		], $license->toArray());
	}

}

(new LicenseTest())->run();
