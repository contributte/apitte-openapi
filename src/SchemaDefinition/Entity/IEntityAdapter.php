<?php declare(strict_types = 1);

namespace Apitte\OpenApi\SchemaDefinition\Entity;

interface IEntityAdapter
{

	/**
	 * @param mixed $object
	 * @return mixed
	 */
	public function getMetadata($object);

}
