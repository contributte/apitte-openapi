<?php declare(strict_types = 1);

namespace Apitte\OpenApi\Schema;

use Apitte\OpenApi\Schema\Server;

class Servers implements IOpenApiObject
{

	/** @var Server[] */
	private $servers;


	/**
	 * @param Server $description
	 * @return void
	 */
	public function addServer(Server $server): void
	{
		$this->servers[] = $server;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$arr = [];
		foreach($this->servers AS $server) {
			$arr[]= $server->toArray();
		}

		return Utils::create($arr);
	}

}
