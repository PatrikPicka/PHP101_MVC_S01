<?php

namespace Core\Form\Fields;

use Core\Form\Fields\Superclasses\FormField;

class SubmitType extends FormField
{
	/**
	 * @return void
	 */
	public function render(): void
	{
		if (file_exists(ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'submit_type.php')) {
			include ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'submit_type.php';
		} else {
			include ROOT . DS . 'core' . DS . 'form' . DS . 'templates' . DS . 'fields' . DS . 'submit_type.php';
		}
	}
}