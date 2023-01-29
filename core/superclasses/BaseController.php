<?php

namespace Core\Superclasses;

use Core\View;

abstract class BaseController
{
	protected View $view;

	public function __construct()
	{
		$this->view = new View();
	}
}