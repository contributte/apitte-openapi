<?php

namespace Apitte\OpenApi\DI;

use Apitte\Core\DI\Plugin\AbstractPlugin;
use Apitte\Core\DI\Plugin\PluginCompiler;
use Apitte\OpenApi\OpenApiService;
use Apitte\OpenApi\Schema\Contact;
use Apitte\OpenApi\Schema\Info;
use Apitte\OpenApi\Schema\License;
use Apitte\OpenApi\SchemaType\BaseSchemaType;
use Apitte\OpenApi\SchemaType\ISchemaType;
use Apitte\OpenApi\Tracy\SwaggerUIPanel;
use Nette\PhpGenerator\ClassType;

class OpenApiPlugin extends AbstractPlugin
{

	const PLUGIN_NAME = 'openapi';

	/** @var mixed[] */
	protected $defaults = [
		'swagger' => [
			'url' => NULL,
			'expansion' => SwaggerUIPanel::EXPANSION_LIST,
			'filter' => TRUE,
			'title' => 'OpenAPI',
			'panel' => TRUE,
		],
		'info' => [
			'title' => 'Api Docs',
			'description' => NULL,
			'termsOfService' => NULL,
			'contact' => NULL,
			'license' => NULL,
			'version' => '2.0.5-beta',
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
	 * Register services
	 *
	 * @return void
	 */
	public function loadPluginConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$global = $this->compiler->getExtension()->getConfig();
		$config = $this->getConfig();

		$infoContact = NULL;
		if ($config['info']['contact'] !== NULL) {
			$builder->addDefinition($this->prefix('openapi.info.contact'))
				->setType(Contact::class)
				->setFactory(Contact::class, [
						$config['info']['contact']['name'],
						$config['info']['contact']['url'],
						$config['info']['contact']['email'],
					])
				->setAutowired(FALSE);

			$infoContact = '@' . $this->prefix('openapi.info.contact');
		}

		$infoLicense = NULL;
		if ($config['info']['license'] !== NULL) {
			$builder->addDefinition($this->prefix('openapi.info.license'))
				->setType(License::class)
				->setFactory(License::class, [
						$config['info']['license'],
					])
				->setAutowired(FALSE);

			$infoLicense = '@' . $this->prefix('openapi.info.license');
		}

		$builder->addDefinition($this->prefix('openapi.info'))
			->setType(Info::class)
			->setFactory(Info::class, [
				$config['info']['title'],
				$config['info']['description'],
				$config['info']['termsOfService'],
				$infoContact,
				$infoLicense,
				$config['info']['version'],
			])
			->setAutowired(FALSE);

		$builder->addDefinition('openapi.schemaType')
            ->setType(ISchemaType::class)
            ->setFactory(BaseSchemaType::class);

		$builder->addDefinition($this->prefix('openapi'))
			->setFactory(OpenApiService::class, [
				1 => '@' . $this->prefix('openapi.info'),
			]);

		if ($global['debug'] !== TRUE) return;

		if ($config['swagger']['panel']) {
			$builder->addDefinition($this->prefix('swagger.panel'))
				->setFactory(SwaggerUIPanel::class)
				->addSetup('setUrl', [$config['swagger']['url']])
				->addSetup('setExpansion', [$config['swagger']['expansion']])
				->addSetup('setFilter', [$config['swagger']['filter']])
				->addSetup('setTitle', [$config['swagger']['title']])
				->setAutowired(FALSE);
		}
	}

	/**
	 * @param ClassType $class
	 * @return void
	 */
	public function afterPluginCompile(ClassType $class)
	{
		$global = $this->compiler->getExtension()->getConfig();
		if ($global['debug'] !== TRUE) return;

		$config = $this->getConfig();

		$initialize = $class->getMethod('initialize');
		if ($config['swagger']['panel']) {
			$initialize->addBody('$this->getService(?)->addPanel($this->getService(?));', ['tracy.bar', $this->prefix('swagger.panel')]);
		}
	}

}
