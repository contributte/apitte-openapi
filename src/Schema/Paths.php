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
	 * @return mixed[]
	 */
	public function toArray()
	{
		return Utils::fromArray($this->paths);
	}

}
