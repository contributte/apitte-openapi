<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

class Paths implements IOpenApiObject
{

	/** @var PathItem[] */
	private $paths = [];

	public function setPathItem(string $path, PathItem $pathItem): void
	{
		$this->paths[$path] = $pathItem;
	}

	public function getPath(string $path): ?PathItem
	{
		return array_key_exists($path, $this->paths) ? $this->paths[$path] : null;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return Utils::fromArray($this->paths);
	}

}
