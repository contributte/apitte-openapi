<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Utils
{

	/**
	 * @return mixed[]|null
	 */
	public static function fromNullable(?IOpenApiObject $object): ?array
	{
		return $object !== null ? $object->toArray() : null;
	}

	/**
	 * @param IOpenApiObject[]|mixed[] $objects
	 * @return mixed[]
	 */
	public static function fromArray(array $objects): array
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
	public static function create(array $data): array
	{
		return array_filter($data);
	}

}
