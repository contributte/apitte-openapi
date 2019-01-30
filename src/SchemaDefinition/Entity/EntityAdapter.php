<?php declare(strict_types = 1);

namespace Apitte\OpenApi\SchemaDefinition\Entity;

use Nette\Utils\Strings;
use ReflectionClass;
use ReflectionProperty;

class EntityAdapter implements IEntityAdapter
{

	/**
	 * @param mixed $object
	 * @return mixed
	 */
	public function getMetadata($object)
	{
		if (Strings::endsWith($object, '[]')) {
			$object = Strings::replace($object, '#\\[\\]#');
			return [
				'type' => 'array',
				'items' => [
					'type' => 'object',
					'properties' => $this->getProperties($object),
				],
			];
		}
		return [
			'type' => 'object',
			'properties' => $this->getProperties($object),
		];
	}

	/**
	 * @param mixed $object
	 * @return mixed
	 */
	protected function getProperties($object)
	{
		$ref = new ReflectionClass($object);
		$properties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
		$data = [];
		foreach ($properties as $property) {
			// TODO resolve type
			$data[$property->getName()] = ['type' => 'string'];
		}
		return $data;
	}

}
