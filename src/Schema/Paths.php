<?php

namespace Apitte\OpenApi\Schema;

class Paths implements IOpenApiObject
{

	/** @var PathItem[] */
	private $paths = [];

	/**
	 * @param string $path
	 * @param PathItem $pathItem
	 * @return void
	 */
	public function setPathItem($path, PathItem $pathItem)
	{
		$this->paths[$path] = $pathItem;
	}

	/**
	 * @param string $path
	 *
	 * @return PathItem|null
	 */
	public function getPath($path)
	{
		return array_key_exists($path, $this->paths) ? $this->paths[$path] : NULL;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::fromArray($this->paths);
	}

}
