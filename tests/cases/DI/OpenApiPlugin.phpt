<?php

/**
 * Test: DI\OpenApiPlugin
 */

use Apitte\Core\DI\ApiExtension;
use Apitte\OpenApi\DI\OpenApiPlugin;
use Apitte\OpenApi\OpenApiService;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

// Default
test(function () {
	$loader = new ContainerLoader(TEMP_DIR, TRUE);
	$class = $loader->load(function (Compiler $compiler) {
		$compiler->addExtension('api', new ApiExtension());
		$compiler->addConfig([
			'parameters' => [
				'debugMode' => TRUE,
			],
			'api' => [
				'plugins' => [
					OpenApiPlugin::class => [],
				],
			],
		]);
	}, 1);

	/** @var Container $container */
	$container = new $class();

	Assert::type(OpenApiService::class, $container->getByType(OpenApiService::class));
});
