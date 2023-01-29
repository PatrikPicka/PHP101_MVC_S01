<?php

namespace Core\Form;

use Core\Constants\FormConstants;
use Core\Form\Fields\Superclasses\FormField;
use Core\Request;

class FormBuilder
{
	/**
	 * @var array
	 */
	protected array $fields = [];

	/**
	 * @var string
	 */
	protected string $action;

	/**
	 * @var string
	 */
	protected string $method;

	protected ?Request $request = null;

	public function __construct(string $action = '', string $method = FormConstants::FORM_METHOD_POST)
	{
		$this->action = $action;
		$this->method = $method;
	}

	public function add(string $name, string $fieldClassName, array $params): FormBuilder
	{
		/** @var FormField $formField */
		$formField = new $fieldClassName(name: $name, params: $params);
		$this->fields[$name] = $formField;

		return $this;
	}

	/**
	 * @return void
	 */
	public function render(): void
	{
		if (file_exists(ROOT . DS . 'templates' . DS . 'form' .  DS . 'form.php')) {
			include ROOT . DS . 'templates' . DS . 'form' .  DS . 'form.php';
		} else {
			include ROOT . DS . 'core' . DS . 'form' . DS . 'templates' . DS . 'form.php';
		}
	}

	/**
	 * @return bool
	 */
	public function isSubmitted(): bool
	{
		return $this->request->isSubmitted(array_key_first($this->fields));
	}

	/**
	 * @return bool
	 */
	public function isValid(): bool
	{
		$return = true;
		foreach ($this->fields as $field) {
			if (!$field->runAsserts()) {
				$return = false;
			}
		}

		return $return;
	}

	/**
	 * @param Request $request
	 * @return void
	 */
	public function handleRequest(Request $request): void
	{
		$this->request = $request;

		foreach ($this->fields as $fieldName => $field) {
			if ($request->isSubmitted(name: $fieldName)) {
				$value = $request->get($fieldName);
				$this->fields[$fieldName]->setValue(value: $value);

				foreach ($this->fields[$fieldName]->getAsserts() as $assert) {
					$assert->setValue($value);
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}
}