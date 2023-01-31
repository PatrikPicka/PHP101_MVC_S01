<?php

namespace Core\Form\Fields\Superclasses;

use Core\Asserts\Superclasses\BaseAssert;
use Core\Constants\FormConstants;
use Core\Router;
use Core\Session;
use Couchbase\PathNotFoundException;

abstract class FormField
{
	/**
	 * @var string
	 */
	protected string $label;

	/**
	 * @var string
	 */
	protected string $name;

	/**
	 * @var mixed
	 */
	protected mixed $value;

	/**
	 * @var string
	 */
	protected string $fieldClasses;

	/**
	 * @var string
	 */
	protected string $fieldWrapperClasses;

	/**
	 * @var BaseAssert[]|array
	 */
	protected array $asserts = [];

	public function __construct(string $name, array $params)
	{
		$this->value = $params['data'] ?? null;

		$this->setFieldClasses(fieldClasses: $params['attr']['class'] ?? '');
		$this->setFieldWrapperClasses(fieldWrapperClasses: $params['attr']['parent-class'] ?? '');

		$asserts = [];
		if (!empty($params['asserts'])) {
			foreach ($params['asserts'] as $assert) {
				if ($assert instanceof BaseAssert) {
					$asserts[] = $assert;
				} else {
					$asserts[] = new $assert(fieldName: $name, value: $this->value);
				}
			}
		}
		$this->setAsserts(asserts: $asserts);

		$this->label = $this->getFieldLabel(params: $params);
		$this->name = $name;
	}

	abstract public function render();

	/**
	 * @return bool
	 */
	public function runAsserts(): bool
	{
		foreach ($this->asserts as $assert) {
			if (!$assert->runAssert()) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function isInvalid(): bool
	{
		return !empty(Session::get(name: FormConstants::FORM_ERROR_MESSAGE_SESSION_NAME)[$this->getName()]);
	}

	/**
	 * @return string
	 */
	public function displayErrorMessage(): string
	{
		$fullErrorSessionData = Session::flash(name: FormConstants::FORM_ERROR_MESSAGE_SESSION_NAME);
		$messageForField = $fullErrorSessionData[$this->name];
		unset($fullErrorSessionData[$this->name]);
		Session::set(FormConstants::FORM_ERROR_MESSAGE_SESSION_NAME, $fullErrorSessionData);

		return $messageForField;
	}

	/**
	 * @param array $params
	 * @return string
	 * @throws PathNotFoundException
	 */
	protected function getFieldLabel(array $params): string
	{
		if (empty($params[FormConstants::FIELD_LABEL])) {
			DEBUG ? die ('Missing label inside params of field type: ' . get_class($this)) : Router::route(params: ['error', 'internalServerError']);
		}

		return $params[FormConstants::FIELD_LABEL];
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel(string $label): void
	{
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getValue(): mixed
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue(mixed $value): void
	{
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getFieldClasses(): string
	{
		return $this->fieldClasses;
	}

	/**
	 * @param string $fieldClasses
	 */
	public function setFieldClasses(string $fieldClasses): void
	{
		$this->fieldClasses = $fieldClasses;
	}

	/**
	 * @return string
	 */
	public function getFieldWrapperClasses(): string
	{
		return $this->fieldWrapperClasses;
	}

	/**
	 * @param string $fieldWrapperClasses
	 */
	public function setFieldWrapperClasses(string $fieldWrapperClasses): void
	{
		$this->fieldWrapperClasses = $fieldWrapperClasses;
	}

	/**
	 * @return array
	 */
	public function getAsserts(): array
	{
		return $this->asserts;
	}

	/**
	 * @param array $asserts
	 */
	public function setAsserts(array $asserts): void
	{
		$this->asserts = $asserts;
	}

	/**
	 * @param BaseAssert $assert
	 * @return void
	 */
	public function addAssert(BaseAssert $assert): void
	{
		$this->asserts[] = $assert;
	}
}