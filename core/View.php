<?php

namespace Core;

use Couchbase\PathNotFoundException;

class View
{
	protected array $content;
	protected string $siteTitle = SITE_TITLE;
	protected ?string $outputBuffer = null;
	protected string $layout = DEFAULT_LAYOUT;

	/**
	 * @param string $template
	 * @param array $params
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function render(string $template, array $params = []): void
	{
		foreach ($params as $key => $param) {
			$$key = $param;
		}

		$templateParts = explode('/', $template);
		$templatePath = implode(DS, $templateParts);

		if (file_exists(ROOT . DS . 'templates' . DS . $templatePath . '.php')) {
			include ROOT . DS . 'templates' . DS . $templatePath . '.php';
			include ROOT . DS . 'templates' . DS . 'layouts' . DS . $this->layout . '.php';
		} else {
			DEBUG ? die('The view ' . $templatePath . ' does not exists.') : Router::route(['error', 'internalServerError']);
		}
	}

	/**
	 * @param string $template
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function includeTemplate(string $template): void
	{
		$templateParts = explode('/', $template);
		$templatePath = implode(DS, $templateParts);

		if (file_exists(ROOT . DS . 'templates' . DS . $templatePath . '.php')) {
			include ROOT . DS . 'templates' . DS . $templatePath . '.php';
		} else {
			DEBUG ? die('The view ' . $templatePath . ' does not exists.') : Router::route(['error', 'internalServerError']);
		}
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function block(string $name): ?string
	{
		if (isset($this->content[$name])) {
			return $this->content[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function start(string $name): void
	{
		$this->outputBuffer = $name;
		ob_start();
	}

	/**
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function end(): void
	{
		if ($this->outputBuffer !== null) {
			$this->content[$this->outputBuffer] = ob_get_clean();
		} else {
			DEBUG ? die('You must first start a block.') : Router::route(['error', 'internalServerError']);
		}
	}

	/**
	 * @return string
	 */
	public function siteTitle(): string
	{
		return $this->siteTitle;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setSiteTitle(string $title): void
	{
		$this->siteTitle = $title;
	}

	/**
	 * @param string $layou
	 * @return void
	 */
	public function setLayout(string $layou): void
	{
		$this->layout = $layou;
	}
}