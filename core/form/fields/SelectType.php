<?php

namespace Core\Form\Fields;

use Core\Constants\FormConstants;
use Core\Form\Fields\Superclasses\FormField;
use Core\Router;

class SelectType extends FormField
{
	/**
	 * @var array|mixed
	 */
	protected array $choices = [];

	public function __construct(string $name, array $params)
	{
		if (empty($params[FormConstants::SELECT_TYPE_CHOICES])) {
			DEBUG ? die('Missing choices inside params of field type:' . TextType::class) : Router::route(params: ['error', 'internalServerError']);
		}
		$this->choices = $params['choices'];

		parent::__construct(name: $name, params: $params);
	}

	/**
	 * @return void
	 */
	public function render(): void
	{
		if (file_exists(ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'select_type.php')) {
			include ROOT . DS . 'templates' . DS . 'form' . DS . 'fields' . DS . 'select_type.php';
		} else {
			include ROOT . DS . 'core' . DS . 'form' . DS . 'templates' . DS . 'fields' . DS . 'select_type.php';
		}
	}
}