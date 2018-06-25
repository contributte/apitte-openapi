<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class OpenApi implements IOpenApiObject
{

	public const VERSION = '3.0.0';

	/** @var string */
	private $openapi = self::VERSION;

	/** @var Info */
	private $info;

	/** @var Server[] */
	private $servers = [];

	/** @var Paths */
	private $paths;

	/** @var Components|null */
	private $components;

	/** @var SecurityRequirement[] */
	private $security = [];

	/** @var Tag[] */
	private $tags = [];

	/** @var ExternalDocumentation|null */
	private $externalDocs;

	public function __construct(Info $info, Paths $paths)
	{
		$this->info = $info;
		$this->paths = $paths;
	}

	public function addTag(Tag $tag): void
	{
		$this->tags[] = $tag;
	}

	public function addServer(Server $server): void
	{
		$this->servers[] = $server;
	}

	public function setComponents(?Components $components): void
	{
		$this->components = $components;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::create([
			'openapi' => $this->openapi,
			'info' => $this->info->toArray(),
			'servers' => Utils::fromArray($this->servers),
			'paths' => $this->paths->toArray(),
			'components' => Utils::fromNullable($this->components),
			'security' => Utils::fromArray($this->security),
			'tags' => Utils::fromArray($this->tags),
			'externalDocs' => Utils::fromNullable($this->externalDocs),
		]);
	}

}
