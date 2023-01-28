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
				$request = new Request(controller: $controllerName, action: $action, postData:  $_POST, queryData:  $_GET);

				call_user_func([$dispatch, $action], $request, $params);
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
	 * @return array
	 */
	public static function getMenu(): array
	{
		$menuItems = yaml_parse_file(ROOT . DS . 'app' . DS . 'menu.yaml');
		$finalMenuArray = [];

		foreach ($menuItems as $menuItem => $data) {
			if (array_key_first($data) === 'dropdown') {
				foreach ($data['dropdown'] as $subMenuItem => $subData) {
					if ($subMenuItem === 'divider' && !empty($finalMenuArray[$menuItem])) {
						$finalMenuArray[$menuItem][$subMenuItem] = null;
					} elseif ($subMenuItem !== 'divider' && ($link = self::getLink(data: $subData)) !== null) {
						$finalMenuArray[$menuItem][$subMenuItem] = $link;
					}
				}
			} elseif (($link = self::getLink(data: $data)) !== null) {
				$finalMenuArray[$menuItem] = $link;
			}
		}

		return $finalMenuArray;
	}

	/**
	 * @param array $data
	 * @return string|null
	 */
	public static function getLink(array $data): ?string
	{
		if (isset($data['link'])) {
			return $data['link'];
		} else {
			$controller = strtolower($data['controller']);
			$action = $data['action'] ?? DEFAULT_ACTION_BASE_NAME;

			if (self::hasAccess(controller: $controller, action: $action)) {
				if (!empty($data['params'])) {
					$params = '';
					foreach ($data['params'] as $param) {
						$params .= '/' . $param;
					}
				}

				return PROOT . $controller . ($action !== DEFAULT_ACTION_BASE_NAME || isset($params) ? '/' . $action : null) . ($params ?? null);
			}

			return null;
		}
	}

	/**
	 * @param array $params
	 * @return void
	 */
	public static function redirect(array $params): void
	{
		$route = self::getLink(data: $params);

		if (!headers_sent()) {
			header('Location: ' . $route);
			exit();
		} else {
			echo '
				<script type="text/javascript">
					window.location.href = ' . $route. '
				</script>
				<noscript>
					<meta http-equiv="refresh" content="0;url=' . $route . '" />
				</noscript>
			';
			exit();
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
			$request = new Request(controller: DEFAULT_CONTROLLER, action: DEFAULT_ACTION, postData:  $_POST, queryData:  $_GET);

			call_user_func([$dispatch, DEFAULT_ACTION], $request);
		}
	}
}