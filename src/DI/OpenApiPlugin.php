<?php

namespace Apitte\OpenApi\DI;

use Apitte\Core\DI\Plugin\AbstractPlugin;
use Apitte\Core\DI\Plugin\PluginCompiler;
use Apitte\OpenApi\OpenApiService;
use Apitte\OpenApi\Tracy\SwaggerUIPanel;
use Nette\PhpGenerator\ClassType;

class OpenApiPlugin extends AbstractPlugin
{

	const PLUGIN_NAME = 'openapi';

	/** @var mixed[] */
	protected $defaults = [
		'swaggerUi' => [
			'url' => NULL,
			'expansion' => SwaggerUIPanel::EXPANSION_LIST,
			'filter' => TRUE,
		],
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

		$swaggerUiConfig = $config['swaggerUi'];
		$builder->addDefinition($this->prefix('swagger.panel'))
			->setFactory(SwaggerUIPanel::class)
			->addSetup('setUrl', [$swaggerUiConfig['url']])
			->addSetup('setExpansion', [$swaggerUiConfig['expansion']])
			->addSetup('setFilter', [$swaggerUiConfig['filter']])
			->setAutowired(FALSE);
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
			'$this->getService(?)->addPanel($this->getService(?));',
			['tracy.bar', $this->prefix('swagger.panel')]
		);
	}

}
