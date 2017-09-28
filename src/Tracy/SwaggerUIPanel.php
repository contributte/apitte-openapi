<?php

namespace Apitte\OpenApi\Tracy;

use Apitte\OpenApi\OpenApiService;
use Tracy\IBarPanel;

class SwaggerUIPanel implements IBarPanel
{

	const EXPANSION_FULL = 'full';
	const EXPANSION_LIST = 'list';
	const EXPANSION_NONE = 'none';

	/** @var string|NULL */
	private $url;

	/** @var string */
	private $expansion = self::EXPANSION_LIST;

	/** @var string|bool */
	private $filter;

	/** @var OpenApiService */
	private $openApiService;

	/**
	 * @param OpenApiService $openApiService
	 */
	public function __construct(OpenApiService $openApiService)
	{
		$this->openApiService = $openApiService;
	}

	/**
	 * @param string|NULL $url
	 * @return void
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @param string $expansion
	 * @return void
	 */
	public function setExpansion($expansion)
	{
		$this->expansion = $expansion;
	}

	/**
	 * @param string|bool $filter
	 * @return void
	 */
	public function setFilter($filter)
	{
		$this->filter = $filter;
	}

	/**
	 * Renders HTML code for custom tab.
	 *
	 * @return string
	 */
	public function getTab()
	{
		ob_start();
		require __DIR__ . '/templates/tab.phtml';

		return ob_get_clean();
	}

	/**
	 * Renders HTML code for custom panel.
	 *
	 * @return string
	 */
	public function getPanel()
	{
		ob_start();
		$spec = $this->openApiService->createSchema()->toArray();
		$url = $this->url;
		$expansion = $this->expansion;
		$filter = $this->filter;
		require __DIR__ . '/templates/panel.phtml';

		return ob_get_clean();
	}

}
