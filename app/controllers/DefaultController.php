<?php

namespace App\Controllers;

use Core\View;

class DefaultController
{
	public function indexAction(?array $params = null)
	{
		$view = new View();
		$view->render('homepage', [
			'siteTitle' => 'Hello world!',
		]);
	}
}