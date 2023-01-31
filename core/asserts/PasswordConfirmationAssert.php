<?php

namespace Core\Asserts;

use Core\Asserts\Superclasses\BaseAssert;
use Core\Request;

class PasswordConfirmationAssert extends BaseAssert
{
	/**
	 * @var string|null
	 */
	protected ?string $password = null;

	/**
	 * @var string|null
	 */
	protected ?string $confirmPassword = null;

	public function __construct(string $confirmationFieldName, string $fieldName, Request $request)
	{
		$this->password = $request->get($confirmationFieldName);
		$this->confirmPassword = $request->get($fieldName);

		parent::__construct($fieldName, $this->confirmPassword);
	}

	/**
	 * @return bool
	 */
	public function runAssert(): bool
	{
		if ($this->password !== $this->confirmPassword) {
			$this->setErrorMessageForField('Passwords must match.');

			return false;
		}

		return true;
	}
}