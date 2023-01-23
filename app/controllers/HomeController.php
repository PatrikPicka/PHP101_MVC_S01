<?php

namespace App\Controllers;

use Core\View;

class HomeController
{
	public function indexAction(?array $params = null)
	{
		$view = new View();
		$view->render('pages/home');
	}
}