<?php

namespace App\Controllers;

use Core\Router;

class UserController
{
	/**
	 * @return void
	 */
	public function loginAction(): void
	{
		$_SESSION[LOGGED_IN_USER_SESSION] = 30;

		Router::redirect([
			'controller' => 'home',
		]);
	}

	/**
	 * @return void
	 */
	public function logoutAction(): void
	{
		unset($_SESSION[LOGGED_IN_USER_SESSION]);

		Router::redirect([
			'controller' => 'home',
		]);
	}
}