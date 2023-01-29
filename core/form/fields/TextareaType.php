<?php

namespace Core\Form\Fields;

use Core\Form\Fields\Superclasses\FormField;

class TextareaType extends FormField
{
	protected ?string $placeHolder = null;

	public function __construct(string $name, array $params)
	{
		if (!empty($params['placeholder'])) {
			$this->placeHolder = $params['placeholder'];
		}

		parent::__construct(name: $name, params: $params);
	}

	/**
	 * @return void
	 */
	public function render(): void
	{
		if (file_exists(ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'textarea_type.php')) {
			include ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'textarea_type.php';
		} else {
			include ROOT . DS . 'core' . DS . 'form' . DS . 'templates' . DS . 'fields' . DS . 'textarea_type.php';
		}
	}

	/**
	 * @return string|null
	 */
	public function getPlaceHolder(): ?string
	{
		return $this->placeHolder;
	}
}