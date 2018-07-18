<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class PathItem implements IOpenApiObject
{

	public const
		OPERATION_GET = 'get',
		OPERATION_PUT = 'put',
		OPERATION_POST = 'post',
		OPERATION_DELETE = 'delete',
		OPERATION_OPTIONS = 'options',
		OPERATION_HEAD = 'head',
		OPERATION_PATCH = 'patch',
		OPERATION_TRACE = 'trace';

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

	/** @var string */
	private $ref;

	/** @var string|null */
	private $summary;

	/** @var string|null */
	private $description;

	/** @var Operation[] */
	private $operations = [];

	/** @var Server[] */
	private $servers = [];

	/** @var Parameter|Reference */
	private $params;

	public function setOperation(string $key, Operation $operation): void
	{
		if (!in_array($key, self::$allowedOperations, true)) {
			return;
		}

		$this->operations[$key] = $operation;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
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
