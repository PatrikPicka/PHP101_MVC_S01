<?php

namespace Core\Asserts;

use Core\Asserts\Superclasses\BaseAssert;

class NotEmptyAssert extends BaseAssert
{
	public function runAssert(): bool
	{
		if (empty($this->getValue())) {
			$this->setErrorMessageForField(message: 'This field cannot be empty.');

			return false;
		}

		return true;
	}
}