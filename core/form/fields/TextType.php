<?php

namespace Core\Form\Fields;

use Core\Constants\FormConstants;
use Core\Form\Fields\Superclasses\FormField;

class TextType extends FormField
{
	/**
	 * @var string
	 */
	protected string $type = FormConstants::TEXT_TYPE;

	/**
	 * @var string|null
	 */
	protected ?string $placeHolder = null;

	public function __construct(string $name, array $params)
	{
		if (!empty($params['placeholder'])) {
			$this->placeHolder = $params['placeholder'];
		}

		if (!empty($params['type'])) {
			$this->type = $params['type'];
		}

		parent::__construct($name, $params);
	}

	/**
	 * @return void
	 */
	public function render(): void
	{
		if (file_exists(ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'text_type.php')) {
			include ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'text_type.php';
		} else {
			include ROOT . DS . 'core' . DS . 'form' . DS . 'templates' . DS . 'fields' . DS . 'text_type.php';
		}
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return string|null
	 */
	public function getPlaceHolder(): ?string
	{
		return $this->placeHolder;
	}
}