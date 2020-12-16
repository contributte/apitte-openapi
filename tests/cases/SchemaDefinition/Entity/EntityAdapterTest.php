<?php declare(strict_types = 1);

namespace Tests\Apitte\OpenApi\cases\SchemaDefinition\Entity;

use Apitte\OpenApi\SchemaDefinition\Entity\EntityAdapter;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Tester\Assert;
use Tester\TestCase;
use Tests\Apitte\OpenApi\Fixtures\ResponseEntity\CompoundResponseEntity;
use Tests\Apitte\OpenApi\Fixtures\ResponseEntity\SelfReferencingEntity;

require_once __DIR__ . '/../../../bootstrap.php';

final class EntityAdapterTest extends TestCase
{

	public function testComplex(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			[
				'anyOf' => [
					['type' => 'integer'],
					['type' => 'number'],
					[
						'type' => 'object',
						'properties' => [
							'nullableObjects' => [
								'nullable' => true,
								'type' => 'array',
								'items' => [
									'type' => 'object',
									'properties' => [
										'int' => ['type' => 'integer'],
										'nullableFloat' => ['nullable' => true, 'type' => 'number'],
										'string' => ['type' => 'string'],
										'bool' => ['type' => 'boolean'],
										'datetime' => ['type' => 'string', 'format' => 'date-time'],
										'mixed' => ['nullable' => true],
										'untypedProperty' => ['type' => 'string'],
									],
								],
							],
							'unionProperties' => [
								'oneOf' => [
									['type' => 'array', 'items' => ['type' => 'string']],
									['type' => 'array', 'items' => ['type' => 'integer']],
								],
							],
							'intersectionProperties' => [
								'allOf' => [
									['type' => 'array', 'items' => ['type' => 'string']],
									['type' => 'array', 'items' => ['type' => 'integer']],
								],
							],
							'unionAndIntersectionProperties' => [
								'anyOf' => [
									['type' => 'array', 'items' => ['type' => 'string']],
									['type' => 'array', 'items' => ['type' => 'integer']],
									['type' => 'array', 'items' => ['type' => 'number']],
								],
							],
						],
					],
				],
			],
			$adapter->getMetadata('(int&float)|' . CompoundResponseEntity::class)
		);
	}

	public function testAlternativeSyntax(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			['oneOf' => [['type' => 'integer'], ['type' => 'number'], ['type' => 'boolean']]],
			$adapter->getMetadata('integer|double|boolean')
		);
	}

	public function testDuplicates(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			['oneOf' => [['type' => 'boolean'], ['type' => 'number']]],
			$adapter->getMetadata('bool|boolean|true|false|double|float|numeric')
		);

		Assert::same(
			['type' => 'boolean'],
			$adapter->getMetadata('bool|boolean|true|false')
		);
	}

	public function testObject(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			['type' => 'object'],
			$adapter->getMetadata('object')
		);

		Assert::same(
			['type' => 'object'],
			$adapter->getMetadata(DateTimeInterface::class)
		);
	}

	public function testMixed(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			['nullable' => true],
			$adapter->getMetadata('mixed')
		);
	}

	public function testDateTime(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			['type' => 'string', 'format' => 'date-time'],
			$adapter->getMetadata(DateTimeImmutable::class)
		);

		Assert::same(
			['type' => 'string', 'format' => 'date-time'],
			$adapter->getMetadata(DateTime::class)
		);
	}

	public function testArray(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			['type' => 'array', 'items' => ['type' => 'string']],
			$adapter->getMetadata('string[]')
		);
	}

	public function testSelfReference(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			[
				'type' => 'object',
				'properties' => [
					'selfReference' => ['type' => 'object'],
					'staticReference' => ['type' => 'object'],
					'classNameReference' => ['type' => 'object'],
					'normalProperty' => ['type' => 'string'],
				],
			],
			$adapter->getMetadata(SelfReferencingEntity::class)
		);
	}

	public function testBadCase(): void
	{
		$adapter = new EntityAdapter();

		Assert::same(
			['oneOf' => [['type' => 'boolean'], ['type' => 'string']]],
			$adapter->getMetadata('bOOl|STRing')
		);
	}

}

(new EntityAdapterTest())->run();
