<?php
namespace Core;

use Couchbase\PathNotFoundException;

class Router
{
	/**
	 * @param array $params
	 * @return void
	 * @throws PathNotFoundException
	 */
	public static function route(array $params):void
	{
		if (!isset($params[array_key_first($params)]) ||$params[array_key_first($params)] === '') {
			self::renderDefaultController();
		} else {
			$controllerName = ucwords($params[array_key_first($params)]) . 'Controller';
			$controllerWithNamespace = BASE_CONTROLLER_NAMESPACE . $controllerName;
			array_shift($params);
			$action = (isset($params[array_key_first($params)])) ? $params[array_key_first($params)] . 'Action' : DEFAULT_ACTION;

			array_shift($params);

			if (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . $controllerName . '.php')) {
				require ROOT . DS . 'app' . DS . 'controllers' . DS . $controllerName . '.php';
			} else {
				DEBUG ? die('This controller does not exist: ' . $controllerName) : throw new PathNotFoundException('This path does not exists');
			}

			$dispatch = new $controllerWithNamespace;
			if (method_exists($dispatch, $action)) {
				call_user_func([$dispatch, $action], $params);
			} else {
				DEBUG ? die('That method does not exists inside this controller: ' . $controllerWithNamespace . '::' . $action) : throw new PathNotFoundException('This path does not exists');
			}
		}
	}

	/**
	 * @return void
	 */
	private static function renderDefaultController(): void
	{
		require ROOT . DS . 'app' . DS . 'controllers' . DS . DEFAULT_CONTROLLER . '.php';
		$controller = BASE_CONTROLLER_NAMESPACE . DEFAULT_CONTROLLER;

		$dispatch = new $controller();
		if (method_exists($dispatch, DEFAULT_ACTION)) {
			call_user_func([$dispatch, DEFAULT_ACTION]);
		}
	}
}