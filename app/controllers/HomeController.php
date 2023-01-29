<?php

namespace App\Controllers;

use Core\Request;
use Core\Superclasses\BaseController;
use Core\View;

class HomeController extends BaseController
{
	public function indexAction(Request $request, ?array $params = null)
	{
		$view = new View();
		$view->render('pages/home');
	}
}