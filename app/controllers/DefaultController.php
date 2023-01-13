<?php

namespace App\Controllers;

class DefaultController
{
	public function indexAction(?array $params = null)
	{
		var_dump($params); die();
		echo 'Hello from default controller';
	}
}