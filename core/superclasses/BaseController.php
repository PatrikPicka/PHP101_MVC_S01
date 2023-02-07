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

	/**
	 * @param array $resp
	 * @param int $code
	 * @return void
	 */
	public function ajaxResponse(array $resp, int $code = 200): void
	{
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json; charset=UTF-8');
		http_response_code($code);
		echo json_encode($resp);
		exit;
	}
}