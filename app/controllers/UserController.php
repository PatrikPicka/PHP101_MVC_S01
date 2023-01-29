<?php

namespace App\Controllers;

use Core\Asserts\NotEmptyAssert;
use Core\Form\Fields\TextType;
use Core\Form\FormBuilder;
use Core\Request;
use Core\Router;
use Core\Superclasses\BaseController;

class UserController extends BaseController
{
	/**
	 * @return void
	 */
	public function loginAction(Request $request): void
	{
		$form = new FormBuilder();
		$form->add('username', TextType::class, [
			'label' => 'Username:',
			'asserts' => [
				NotEmptyAssert::class,
			],
		]);

		$form->handleRequest(request: $request);

		if ($form->isSubmitted() && $form->isValid()) {
			// Todo: login user
		}

		$this->view->render('pages/user/login', [
			'form' => $form,
		]);
	}

	/**
	 * @return void
	 */
	public function logoutAction(Request $request): void
	{
		unset($_SESSION[LOGGED_IN_USER_SESSION]);

		Router::redirect([
			'controller' => 'home',
		]);
	}
}