<?php

namespace Apitte\OpenApi\Schema;

class PathItem implements IOpenApiObject
{

	const OPERATION_GET = 'get';
	const OPERATION_PUT = 'put';
	const OPERATION_POST = 'post';
	const OPERATION_DELETE = 'delete';
	const OPERATION_OPTIONS = 'options';
	const OPERATION_HEAD = 'head';
	const OPERATION_PATCH = 'patch';
	const OPERATION_TRACE = 'trace';

	/** @var string[] */
	private static $allowedOperations = [
		self::OPERATION_GET,
		self::OPERATION_PUT,
		self::OPERATION_POST,
		self::OPERATION_DELETE,
		self::OPERATION_OPTIONS,
		self::OPERATION_HEAD,
		self::OPERATION_PATCH,
		self::OPERATION_TRACE,
	];

	//ref string
	private $ref;

	/** @var string|NULL */
	private $summary;

	/** @var string|NULL */
	private $description;

	/** @var Operation[] */
	private $operations = [];

	/** @var Server[] */
	private $servers = [];

	//[Parameter Object | Reference Object]
	private $params;

	/**
	 * @param string $key
	 * @param Operation $operation
	 * @return void
	 */
	public function setOperation($key, Operation $operation)
	{
		if (!in_array($key, self::$allowedOperations)) {
			return;
		}

		$this->operations[$key] = $operation;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		$data = [
			'summary' => $this->summary,
			'description' => $this->description,
			'servers' => Utils::fromArray($this->servers),
		];
		$data = array_merge($data, Utils::fromArray($this->operations));
		return Utils::create($data);
	}

}
