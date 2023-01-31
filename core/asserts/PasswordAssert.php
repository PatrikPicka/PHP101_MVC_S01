<?php

namespace Core\Asserts;

use Core\Asserts\Superclasses\BaseAssert;
use Core\Constants\FormConstants;

class PasswordAssert extends BaseAssert
{
	public function runAssert(): bool
	{
		$return = true;
		$length = $this->value !== null ? strlen($this->value) : 0;
		if ($length < FormConstants::PASSWORD_MIN_LENGTH) {
			$this->setErrorMessageForField('Password must be minimally ' . FormConstants::PASSWORD_MIN_LENGTH . ' characters length.');
			$return = false;
		} elseif ($length > FormConstants::PASSWORD_MAX_LENGTH) {
			$this->setErrorMessageForField('Password must be maximally ' . FormConstants::PASSWORD_MIN_LENGTH . ' characters length.');
			$return = false;
		}

		return $return;
	}
}