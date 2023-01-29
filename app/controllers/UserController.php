<?php

namespace App\Controllers;

use Core\Asserts\EmailAssert;
use Core\Asserts\NotEmptyAssert;
use Core\Asserts\PasswordAssert;
use Core\Form\Fields\SelectType;
use Core\Form\Fields\SubmitType;
use Core\Form\Fields\TextareaType;
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
		$form
			->add('username', TextType::class, [
				'label' => 'Username:',
				'asserts' => [
					NotEmptyAssert::class,
				],
			])
			->add('password', TextType::class, [
				'label' => 'Password',
				'type' => 'password',
				'asserts' => [
					PasswordAssert::class,
				],
			])
			->add('textarea', TextareaType::class, [
				'label' => 'Textarea type field',
				'asserts' => [
					NotEmptyAssert::class,
					EmailAssert::class,
				],
			])
			->add('select', SelectType::class, [
				'label' => 'Select type field',
				'choices' => [
					'Value 1' => 1,
					'Value 2' => 2,
					'Value 3' => 3,
				]
			])
			->add('submit', SubmitType::class, [
				'label' => 'Přihlásit',
				'attr' => [
					'class' => 'float-right',
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