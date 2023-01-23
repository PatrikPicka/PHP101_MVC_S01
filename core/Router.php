<?php
namespace Core;

use App\Models\User;
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
			$controllerBaseName = ucwords($params[array_key_first($params)]);
			$controllerName = $controllerBaseName . 'Controller';
			$controllerWithNamespace = BASE_CONTROLLER_NAMESPACE . $controllerName;
			array_shift($params);
			$actionBaseName = (isset($params[array_key_first($params)]) && !empty($params[array_key_first($params)])) ? $params[array_key_first($params)] : DEFAULT_ACTION_BASE_NAME;
			$action = $actionBaseName . 'Action';

			array_shift($params);

			if (!self::hasAccess(controller: $controllerBaseName, action: $actionBaseName)) {
				DEBUG ? die('You do not have permissions to this controller or this action:' . $controllerName . '::' . $action) : self::renderDefaultController();
			}

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
	 * @param string $controller
	 * @param string $action
	 * @return bool
	 */
	public static function hasAccess(string $controller, string $action = DEFAULT_ACTION_BASE_NAME): bool
	{
		$acls = yaml_parse_file(ROOT . DS . 'app' . DS . 'acl.yaml');

		$usersAcls = ['guest'];
		if (User::isUserLoggedIn()) {
			$user = new User(id: User::getLoggedInUserId());
			$usersAcls = array_merge($usersAcls, $user->getAcls());
		}

		$aclsFinal = [];
		foreach ($usersAcls as $usersAcl) {
			$aclsFinal = array_merge($aclsFinal, $acls[$usersAcl]);
		}

		$baseControllerName = ucfirst($controller);
		$hasAcces = false;

		foreach ($aclsFinal as $aclKey => $aclValue) {
			if ($aclKey === 'denied' &&
				array_key_exists($baseControllerName, $aclValue) &&
				(in_array($action, $aclValue[$baseControllerName]) ||
					$aclValue[$baseControllerName][array_key_first($aclValue[$baseControllerName])] === '*'))
			{
				break;
			} elseif ($aclKey !== 'denied' && $aclKey === $baseControllerName && (in_array($action, $aclValue) || $aclValue[array_key_first($aclValue) === '*'])) {
				$hasAcces = true;
			}
		}

		return $hasAcces;
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