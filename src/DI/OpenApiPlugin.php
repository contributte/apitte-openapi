<?php declare(strict_types = 1);

namespace Apitte\OpenApi\DI;

use Apitte\Core\DI\Plugin\Plugin;
use Apitte\Core\Exception\Logical\InvalidArgumentException;
use Apitte\OpenApi\SchemaBuilder;
use Apitte\OpenApi\SchemaDefinition\ArrayDefinition;
use Apitte\OpenApi\SchemaDefinition\BaseDefinition;
use Apitte\OpenApi\SchemaDefinition\CoreDefinition;
use Apitte\OpenApi\SchemaDefinition\Entity\EntityAdapter;
use Apitte\OpenApi\SchemaDefinition\JsonDefinition;
use Apitte\OpenApi\SchemaDefinition\NeonDefinition;
use Apitte\OpenApi\SchemaDefinition\YamlDefinition;
use Apitte\OpenApi\Tracy\SwaggerUIPanel;
use Contributte\DI\Helper\ExtensionDefinitionsHelper;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\Statement;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\Utils\Strings;
use stdClass;

/**
 * @property-read stdClass $config
 */
class OpenApiPlugin extends Plugin
{

	public static function getName(): string
	{
		return 'openapi';
	}

	protected function getConfigSchema(): Schema
	{
		return Expect::structure([
			'definitions' => Expect::arrayOf(Expect::type('string|array|' . Statement::class)),
			'definition' => Expect::array(),
			'files' => Expect::arrayOf('string'),
			'swaggerUi' => Expect::structure([
				'url' => Expect::string()->nullable(),
				'expansion' => Expect::anyOf(...SwaggerUIPanel::EXPANSIONS)->default(SwaggerUIPanel::EXPANSION_LIST),
				'filter' => Expect::bool(true),
				'title' => Expect::string('OpenAPI'),
				'panel' => Expect::bool(false),
			]),
		]);
	}

	/**
	 * Register services
	 */
	public function loadPluginConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$global = $this->compiler->getExtension()->getConfig();
		$config = $this->config;
		$definitionHelper = new ExtensionDefinitionsHelper($this->compiler->getExtension()->getCompiler());

		$builder->addDefinition($this->prefix('entityAdapter'))
			->setFactory(EntityAdapter::class);

		$coreDefinition = $builder->addDefinition($this->prefix('coreDefinition'))
			->setFactory(CoreDefinition::class);

		$schemaBuilder = $builder->addDefinition($this->prefix('schemaBuilder'))
			->setFactory(SchemaBuilder::class);

		if ($config->definitions === []) {
			$schemaBuilder
				->addSetup('addDefinition', [new BaseDefinition()])
				->addSetup('addDefinition', [$coreDefinition]);
			foreach ($config->files as $file) {
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

			$schemaBuilder->addSetup('addDefinition', [new ArrayDefinition($config->definition)]);
		} else {
			foreach ($config->definitions as $definitionName => $definitionConfig) {
				$definitionPrefix = $this->prefix('definition.' . $definitionName);
				$definition = $definitionHelper->getDefinitionFromConfig($definitionConfig, $definitionPrefix);

				if ($definition instanceof Definition) {
					$definition->setAutowired(false);
				}

				$schemaBuilder->addSetup('addDefinition', [$definition]);
			}
		}

		if (!$global->debug) {
			return;
		}

		if ($config->swaggerUi->panel) {
			$builder->addDefinition($this->prefix('swaggerUi.panel'))
				->setFactory(SwaggerUIPanel::class)
				->addSetup('setUrl', [$config->swaggerUi->url])
				->addSetup('setExpansion', [$config->swaggerUi->expansion])
				->addSetup('setFilter', [$config->swaggerUi->filter])
				->addSetup('setTitle', [$config->swaggerUi->title])
				->setAutowired(false);
		}
	}

	public function afterPluginCompile(ClassType $class): void
	{
		$global = $this->compiler->getExtension()->getConfig();
		if (!$global->debug) {
			return;
		}

		$config = $this->config;

		$initialize = $class->getMethod('initialize');
		if ($config->swaggerUi->panel) {
			$initialize->addBody('$this->getService(?)->addPanel($this->getService(?));', [
				'tracy.bar',
				$this->prefix('swaggerUi.panel'),
			]);
		}
	}

}
