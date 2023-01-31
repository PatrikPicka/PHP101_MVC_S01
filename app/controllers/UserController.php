<?php

namespace App\Controllers;

use App\Models\User;
use Core\Asserts\EmailAssert;
use Core\Asserts\NotEmptyAssert;
use Core\Asserts\PasswordAssert;
use Core\Asserts\PasswordConfirmationAssert;
use Core\Form\Fields\SubmitType;
use Core\Form\Fields\TextType;
use Core\Form\FormBuilder;
use Core\Request;
use Core\Router;
use Core\Session;
use Core\Superclasses\BaseController;
use Couchbase\PathNotFoundException;

class UserController extends BaseController
{
	/**
	 * @return void
	 */
	public function loginAction(Request $request): void
	{
		$form = new FormBuilder();
		$form
			->add(name: 'email', fieldClassName: TextType::class, params: [
				'label' => 'Email',
				'type' => 'email',
				'asserts' => [
					NotEmptyAssert::class,
					EmailAssert::class,
				],
			])
			->add(name: 'password', fieldClassName: TextType::class, params: [
				'label' => 'Password',
				'type' => 'password',
				'asserts' => [
					PasswordAssert::class,
				],
			])
			->add(name: 'submit', fieldClassName: SubmitType::class, params: [
				'label' => 'Přihlásit',
				'attr' => [
					'class' => 'float-right',
				]
			]);

		$form->handleRequest(request: $request);

		if ($form->isSubmitted() && $form->isValid()) {
			$email = $request->get(name: 'email');

			$user = new User();
			if ($user->userLoadedByEmail(email: $email)) {
				if (password_verify($request->get(name: 'password'), $user->password)) {
					Session::set(LOGGED_IN_USER_SESSION, $user->getId());
					Session::setAlertMessage(message: 'Successfully logged in!');

					Router::redirect(params: [
						'controller' => DEFAULT_CONTROLLER_BASE_NAME,
					]);
				} else {
					Session::setAlertMessage(message: 'Wrong password.', type: ALERT_WARNING);
				}
			} else {
				Session::setAlertMessage(message: 'User with that email does not exists.', type: ALERT_WARNING);
			}
		}

		$this->view->render(template: 'pages/user/login', params: [
			'form' => $form,
		]);
	}

	/**
	 * @param Request $request
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function registerAction(Request $request): void
	{
		$form = new FormBuilder();
		$form
			->add(name: 'username', fieldClassName: TextType::class, params: [
				'label' => 'Username',
				'asserts' => [
					NotEmptyAssert::class,
				],
			])
			->add(name: 'email', fieldClassName: TextType::class, params: [
				'label' => 'Email',
				'type' => 'email',
				'asserts' => [
					NotEmptyAssert::class,
					EmailAssert::class,
				],
			])
			->add(name: 'password', fieldClassName: TextType::class, params: [
				'label' => 'Password',
				'type' => 'password',
				'asserts' => [
					PasswordAssert::class,
				],
			])
			->add(name: 'confirm_password', fieldClassName: TextType::class, params: [
				'label' => 'Confirm password',
				'type' => 'password',
				'asserts' => [
					new PasswordConfirmationAssert(confirmationFieldName: 'password', fieldName: 'confirm_password', request: $request),
				]
			])
			->add(name: 'submit', fieldClassName: SubmitType::class, params: [
				'label' => 'Registrovat',
				'attr' => [
					'class' => 'float-right',
				]
			]);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$user = new User();
			$user->username = $request->get(name: 'username');
			$user->email = $request->get(name: 'email');
			$user->password = password_hash($request->get(name: 'password'), PASSWORD_DEFAULT);

			$user->populate();

			if ($user->getId() !== null) {
				Session::set(name: LOGGED_IN_USER_SESSION, value: $user->getId());

				Session::setAlertMessage(message: 'Successfully registered! Enjoy our premium content.');
				Router::redirect(params: [
					'controller' => DEFAULT_CONTROLLER_BASE_NAME,
				]);
			} else {
				Session::setAlertMessage(message: 'There was an problem... Please try again later.', type: ALERT_ERROR);
			}
		}

		$this->view->render(template: 'pages/user/register', params: [
			'form' => $form,
		]);
	}

	/**
	 * @return void
	 */
	public function logoutAction(Request $request): void
	{
		Session::delete(name: LOGGED_IN_USER_SESSION);
		Session::setAlertMessage(message: 'Successfully logged out.');

		Router::redirect(params: [
			'controller' => DEFAULT_CONTROLLER_BASE_NAME,
		]);
	}
}