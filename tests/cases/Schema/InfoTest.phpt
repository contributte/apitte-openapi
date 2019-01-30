<?php declare(strict_types = 1);

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\OpenApi\Schema\Contact;
use Apitte\OpenApi\Schema\Info;
use Apitte\OpenApi\Schema\License;
use Tester\Assert;
use Tester\TestCase;

class InfoTest extends TestCase
{

	public function testOptional(): void
	{
		$title = 'Sample Pet Store App';
		$description = 'This is a sample server for a pet store.';
		$termsOfService = 'http://example.com/terms/';
		$contactData = [
			'name' => 'API Support',
			'url' => 'http://www.example.com/support',
			'email' => 'support@example.com',
		];
		$contact = Contact::fromArray($contactData);
		$licenseData = [
			'name' => 'Apache 2.0',
			'url' => 'https://www.apache.org/licenses/LICENSE-2.0.html',
		];
		$license = License::fromArray($licenseData);
		$version = '1.0.1';

		$info = new Info($title, $version);
		$info->setDescription($description);
		$info->setTermsOfService($termsOfService);
		$info->setContact($contact);
		$info->setLicense($license);

		Assert::same($title, $info->getTitle());
		Assert::same($description, $info->getDescription());
		Assert::same($termsOfService, $info->getTermsOfService());
		Assert::same($contact, $info->getContact());
		Assert::same($license, $info->getLicense());
		Assert::same($version, $info->getVersion());

		// fromArray
		$info = Info::fromArray([
			'title' => $title,
			'description' => $description,
			'termsOfService' => $termsOfService,
			'contact' => $contactData,
			'license' => $licenseData,
			'version' => $version,
		]);

		Assert::same($title, $info->getTitle());
		Assert::same($description, $info->getDescription());
		Assert::same($termsOfService, $info->getTermsOfService());
		Assert::equal($contact, $info->getContact());
		Assert::equal($license, $info->getLicense());
		Assert::same($version, $info->getVersion());

		// toArray
		Assert::same([
			'title' => $title,
			'description' => $description,
			'termsOfService' => $termsOfService,
			'contact' => $contactData,
			'license' => $licenseData,
			'version' => $version,
		], $info->toArray());
	}

	public function testRequired(): void
	{
		$title = 'Sample Pet Store App';
		$version = '1.0.1';

		$info = new Info($title, $version);

		Assert::same($title, $info->getTitle());
		Assert::same(null, $info->getDescription());
		Assert::same(null, $info->getTermsOfService());
		Assert::same(null, $info->getContact());
		Assert::same(null, $info->getLicense());
		Assert::same($version, $info->getVersion());

		// fromArray
		$info = Info::fromArray([
			'title' => $title,
			'version' => $version,
		]);

		Assert::same($title, $info->getTitle());
		Assert::same(null, $info->getDescription());
		Assert::same(null, $info->getTermsOfService());
		Assert::same(null, $info->getContact());
		Assert::same(null, $info->getLicense());
		Assert::same($version, $info->getVersion());

		// toArray
		Assert::same([
			'title' => $title,
			'version' => $version,
		], $info->toArray());
	}

}

(new InfoTest())->run();
