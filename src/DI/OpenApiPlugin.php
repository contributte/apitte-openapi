<?php declare(strict_types = 1);

namespace Apitte\OpenApi\DI;

use Apitte\Core\DI\Plugin\AbstractPlugin;
use Apitte\Core\DI\Plugin\PluginCompiler;
use Apitte\Core\Exception\Logical\InvalidArgumentException;
use Apitte\OpenApi\SchemaBuilder;
use Apitte\OpenApi\SchemaDefinition\ArrayDefinition;
use Apitte\OpenApi\SchemaDefinition\BaseDefinition;
use Apitte\OpenApi\SchemaDefinition\CoreDefinition;
use Apitte\OpenApi\SchemaDefinition\Entity\EntityAdapter;
use Apitte\OpenApi\SchemaDefinition\Entity\EntityDefinition;
use Apitte\OpenApi\SchemaDefinition\JsonDefinition;
use Apitte\OpenApi\SchemaDefinition\NeonDefinition;
use Apitte\OpenApi\SchemaDefinition\YamlDefinition;
use Apitte\OpenApi\Tracy\SwaggerUIPanel;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Strings;

class OpenApiPlugin extends AbstractPlugin
{

	public const PLUGIN_NAME = 'openapi';

	/** @var mixed[] */
	protected $defaults = [
		'definitions' => null,
		'definition' => [],
		'files' => [],
		'swaggerUi' => [
			'url' => null,
			'expansion' => SwaggerUIPanel::EXPANSION_LIST,
			'filter' => true,
			'title' => 'SwaggerUi',
			'panel' => true,
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

		$builder->addDefinition($this->prefix('entityAdapter'))
			->setFactory(EntityAdapter::class);

		$entityDefinition = $builder->addDefinition($this->prefix('entityDefinition'))
			->setFactory(EntityDefinition::class);

		$coreDefinition = $builder->addDefinition($this->prefix('coreDefinition'))
			->setFactory(CoreDefinition::class);

		$schemaBuilder = $builder->addDefinition($this->prefix('schemaBuilder'))
			->setFactory(SchemaBuilder::class);

		if ($config['definitions'] === null) {
			$schemaBuilder
				->addSetup('addDefinition', [new BaseDefinition()])
				->addSetup('addDefinition', [$entityDefinition])
				->addSetup('addDefinition', [$coreDefinition]);
			foreach ($config['files'] as $file) {
				if (Strings::endsWith($file, '.neon')) {
					$schemaBuilder->addSetup('addDefinition', [new NeonDefinition($file)]);
				} elseif (Strings::endsWith($file, '.yaml') || Strings::endsWith($file, '.yml')) {
					$schemaBuilder->addSetup('addDefinition', [new YamlDefinition($file)]);
				} elseif (Strings::endsWith($file, '.json')) {
					$schemaBuilder->addSetup('addDefinition', [new JsonDefinition($file)]);
				} else {
					throw new InvalidArgumentException(sprintf(
						'We cant parse file "%s" - unsupported file type',
						$file
					));
				}
			}

			$schemaBuilder->addSetup('addDefinition', [new ArrayDefinition($config['definition'])]);
		} else {
			foreach ($config['definitions'] as $customDefinition) {
				$schemaBuilder->addSetup('addDefinition', [$customDefinition]);
			}
		}

		if ($global['debug'] !== true) {
			return;
		}

		if ($config['swaggerUi']['panel']) {
			$builder->addDefinition($this->prefix('swaggerUi.panel'))
				->setFactory(SwaggerUIPanel::class)
				->addSetup('setUrl', [$config['swaggerUi']['url']])
				->addSetup('setExpansion', [$config['swaggerUi']['expansion']])
				->addSetup('setFilter', [$config['swaggerUi']['filter']])
				->addSetup('setTitle', [$config['swaggerUi']['title']])
				->setAutowired(false);
		}
	}

	public function afterPluginCompile(ClassType $class): void
	{
		$global = $this->compiler->getExtension()->getConfig();
		if ($global['debug'] !== true) {
			return;
		}
		$config = $this->getConfig();

		$initialize = $class->getMethod('initialize');
		if ($config['swaggerUi']['panel']) {
			$initialize->addBody('$this->getService(?)->addPanel($this->getService(?));', [
				'tracy.bar',
				$this->prefix('swaggerUi.panel'),
			]);
		}
	}

}
