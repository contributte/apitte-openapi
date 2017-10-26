<?php

namespace Apitte\OpenApi\Schema;

class Utils
{

	/**
	 * @param IOpenApiObject|NULL $object
	 * @return mixed[]|NULL
	 */
	public static function fromNullable($object)
	{
		return $object !== NULL ? $object->toArray() : NULL;
	}

	/**
	 * @param IOpenApiObject[]|mixed[] $objects
	 * @return mixed[]
	 */
	public static function fromArray(array $objects)
	{
		$data = [];
		foreach ($objects as $key => $object) {
			if ($object instanceof IOpenApiObject) {
				$data[$key] = $object->toArray();
			} else {
				$data[$key] = $object;
			}
		}
		return $data;
	}

	/**
	 * @param mixed[] $data
	 * @return mixed[]
	 */
	public static function create(array $data)
	{
		return array_filter($data);
	}

}
