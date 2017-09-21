<?php

namespace Apitte\OpenApi\Tracy;

use Tracy\IBarPanel;

class SwaggerUIPanel implements IBarPanel
{

	/** @var string|NULL */
	private $url;

	/**
	 * @param string|NULL $url
	 * @return void
	 */
	public function setUrl($url)
	{
		$this->url = $url;
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
		$url = $this->url;
		require __DIR__ . '/templates/panel.phtml';

		return ob_get_clean();
	}

}
