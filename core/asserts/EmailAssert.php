<?php

namespace Core\Asserts;

use Core\Asserts\Superclasses\BaseAssert;

class EmailAssert extends BaseAssert
{
	/**
	 * @return bool
	 */
	public function runAssert(): bool
	{
		if (!filter_var($this->getValue(), FILTER_VALIDATE_EMAIL)) {
			$this->setErrorMessageForField(message: 'Please enter an valid e-mail.');

			return false;
		}

		return true;
	}
}