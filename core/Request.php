<?php

namespace Core;

class Request
{
	/**
	 * @var string
	 */
	protected string $controller;

	/**
	 * @var string
	 */
	protected string $action;

	protected ?array $postData = null;

	protected ?array $queryData = null;

	public function __construct(string $controller, string $action, array $postData, array $queryData)
	{
		$this->controller = $controller;
		$this->action = $action;
		$this->postData = $postData;
		$this->queryData = $queryData;
	}

	/**
	 * @return string
	 */
	public function getController(): string
	{
		return $this->controller;
	}

	/**
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function get(string $name): mixed
	{
		if (!empty($_POST[$name])) {
			return $_POST[$name];
		} elseif (!empty($_GET[$name])) {
			return $_GET[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function isSubmitted(string $name): bool
	{
		if (isset($_POST[$name]) || isset($_GET[$name])) {
			return true;
		}

		return false;
	}
}