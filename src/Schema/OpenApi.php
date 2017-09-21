<?php

namespace Apitte\OpenApi\Schema;

class OpenApi implements IOpenApiObject
{

	const VERSION = '3.0.0';

	/** @var string */
	private $openapi = self::VERSION;

	/** @var Info */
	private $info;

	/** @var Server[] */
	private $servers = [];

	/** @var Paths */
	private $paths;

	/** @var Components|NULL */
	private $components;

	/** @var SecurityRequirement[] */
	private $security = [];

	/** @var Tag[] */
	private $tags = [];

	/** @var ExternalDocumentation|NULL */
	private $externalDocs;

	/**
	 * @param Info $info
	 * @param Paths $paths
	 */
	public function __construct(Info $info, Paths $paths)
	{
		$this->info = $info;
		$this->paths = $paths;
	}

	//Setters

	/**
	 * @param Tag $tag
	 * @return void
	 */
	public function addTag(Tag $tag)
	{
		$this->tags[] = $tag;
	}

	/**
	 * @param Server $server
	 * @return void
	 */
	public function addServer(Server $server)
	{
		$this->servers[] = $server;
	}

	/**
	 * @param Components|NULL $components
	 * @return void
	 */
	public function setComponents($components)
	{
		$this->components = $components;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
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
