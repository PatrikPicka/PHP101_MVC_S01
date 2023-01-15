<?php

namespace App\Controllers;

class DefaultController
{
	public function indexAction(?array $params = null)
	{
		echo 'Hello from default controller';
	}
}