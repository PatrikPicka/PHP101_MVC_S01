<?php

namespace App\Controllers;

use Core\Request;
use Core\View;

class HomeController
{
	public function indexAction(Request $request, ?array $params = null)
	{var_dump($request);
		$view = new View();
		$view->render('pages/home');
	}
}