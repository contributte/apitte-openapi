<?php declare(strict_types = 1);

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\OpenApi\Schema\Contact;
use Tester\Assert;
use Tester\TestCase;

class ContactTest extends TestCase
{

	public function testOptional(): void
	{
		$name = 'API Support';
		$url = 'http://www.example.com/support';
		$email = 'support@example.com';

		$contact = new Contact();
		$contact->setName($name);
		$contact->setEmail($email);
		$contact->setUrl($url);

		Assert::same($name, $contact->getName());
		Assert::same($url, $contact->getUrl());
		Assert::same($email, $contact->getEmail());

		// fromArray
		$contact = Contact::fromArray([
			'name' => $name,
			'url' => $url,
			'email' => $email,
		]);

		Assert::same($name, $contact->getName());
		Assert::same($url, $contact->getUrl());
		Assert::same($email, $contact->getEmail());

		// toArray
		Assert::same([
			'name' => $name,
			'url' => $url,
			'email' => $email,
		], $contact->toArray());
	}

	public function testRequired(): void
	{
		$contact = new Contact();

		Assert::null($contact->getName());
		Assert::null($contact->getUrl());
		Assert::null($contact->getEmail());

		// fromArray
		$contact = Contact::fromArray([]);

		Assert::null($contact->getName());
		Assert::null($contact->getUrl());
		Assert::null($contact->getEmail());

		// toArray
		Assert::same([], $contact->toArray());
	}

}

(new ContactTest())->run();
