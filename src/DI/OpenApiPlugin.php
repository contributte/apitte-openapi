<?php

namespace Apitte\OpenApi\DI;

use Apitte\Core\DI\Plugin\AbstractPlugin;
use Apitte\Core\DI\Plugin\PluginCompiler;
use Apitte\OpenApi\OpenApiService;
use Apitte\OpenApi\Tracy\SwaggerUIPanel;
use Nette\PhpGenerator\ClassType;

class OpenApiPlugin extends AbstractPlugin
{

	const PLUGIN_NAME = 'debug';

	/** @var mixed[] */
	protected $defaults = [
		'url' => NULL,
	];

	/**
	 * @param PluginCompiler $compiler
	 */
	public function __construct(PluginCompiler $compiler)
	{
		parent::__construct($compiler);
		$this->name = self::PLUGIN_NAME;
	}

	/**
	 * Process and validate config
	 *
	 * @param array $config
	 * @return void
	 */
	public function setupPlugin(array $config = [])
	{
		$this->setupConfig($this->defaults, $config);
	}

	/**
	 * Register services
	 *
	 * @return void
	 */
	public function loadPluginConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$global = $this->compiler->getExtension()->getConfig();
		$config = $this->getConfig();

		$builder->addDefinition($this->prefix('openapi'))
			->setFactory(OpenApiService::class);

		if ($global['debug'] !== TRUE)
			return;

		$builder->addDefinition($this->prefix('swagger.panel'))
			->setFactory(SwaggerUIPanel::class)
			->addSetup('setUrl', [$config['url']])
			->setAutowired(TRUE);
	}

	/**
	 * @param ClassType $class
	 * @return void
	 */
	public function afterPluginCompile(ClassType $class)
	{
		$config = $this->compiler->getExtension()->getConfig();
		if ($config['debug'] !== TRUE)
			return;

		$initialize = $class->getMethod('initialize');
		$initialize->addBody(
			'$this->getService(?)->addPanel($this->getByType(?));',
			['tracy.bar', SwaggerUIPanel::class]
		);
	}

}
