<?php declare(strict_types = 1);

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
test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('api', new ApiExtension());
		$compiler->addConfig([
			'parameters' => [
				'debugMode' => true,
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
