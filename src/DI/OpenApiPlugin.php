<?php declare(strict_types = 1);

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

	public const PLUGIN_NAME = 'openapi';

	/** @var mixed[] */
	protected $defaults = [
		'swagger' => [
			'url' => null,
			'expansion' => SwaggerUIPanel::EXPANSION_LIST,
			'filter' => true,
			'title' => 'OpenAPI',
			'panel' => true,
		],
		'info' => [
			'title' => 'Api Docs',
			'description' => null,
			'termsOfService' => null,
			'contact' => null,
			'license' => null,
			'version' => '2.0.5-beta',
		],
	];

	public function __construct(PluginCompiler $compiler)
	{
		parent::__construct($compiler);
		$this->name = self::PLUGIN_NAME;
	}

	/**
	 * Register services
	 */
	public function loadPluginConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$global = $this->compiler->getExtension()->getConfig();
		$config = $this->getConfig();

		$infoContact = null;
		if ($config['info']['contact'] !== null) {
			$builder->addDefinition($this->prefix('openapi.info.contact'))
				->setType(Contact::class)
				->setFactory(Contact::class, [
						$config['info']['contact']['name'],
						$config['info']['contact']['url'],
						$config['info']['contact']['email'],
					])
				->setAutowired(false);

			$infoContact = '@' . $this->prefix('openapi.info.contact');
		}

		$infoLicense = null;
		if ($config['info']['license'] !== null) {
			$builder->addDefinition($this->prefix('openapi.info.license'))
				->setType(License::class)
				->setFactory(License::class, [
						$config['info']['license'],
					])
				->setAutowired(false);

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
			->setAutowired(false);

		$builder->addDefinition($this->prefix('openapi.schemaType'))
			->setType(ISchemaType::class)
			->setFactory(BaseSchemaType::class);

		$builder->addDefinition($this->prefix('openapi'))
			->setFactory(OpenApiService::class, [
				1 => '@' . $this->prefix('openapi.info'),
			]);

		if ($global['debug'] !== true) return;

		if ($config['swagger']['panel']) {
			$builder->addDefinition($this->prefix('swagger.panel'))
				->setFactory(SwaggerUIPanel::class)
				->addSetup('setUrl', [$config['swagger']['url']])
				->addSetup('setExpansion', [$config['swagger']['expansion']])
				->addSetup('setFilter', [$config['swagger']['filter']])
				->addSetup('setTitle', [$config['swagger']['title']])
				->setAutowired(false);
		}
	}

	public function afterPluginCompile(ClassType $class): void
	{
		$global = $this->compiler->getExtension()->getConfig();
		if ($global['debug'] !== true) return;
		$config = $this->getConfig();

		$initialize = $class->getMethod('initialize');
		if ($config['swagger']['panel']) {
			$initialize->addBody('$this->getService(?)->addPanel($this->getService(?));', ['tracy.bar', $this->prefix('swagger.panel')]);
		}
	}

}
