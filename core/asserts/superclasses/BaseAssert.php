<?php

namespace Core\Asserts\Superclasses;

use Core\Constants\FormConstants;
use Core\Session;

abstract class BaseAssert
{
	/**
	 * @var mixed|null
	 */
	protected mixed $value = null;

	/**
	 * @var string
	 */
	protected string $fieldName;

	public function __construct(string $fieldName, mixed $value)
	{
		$this->fieldName = $fieldName;
		$this->value = $value;
	}

	/**
	 * @return bool
	 */
	abstract public function runAssert(): bool;

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
	public function getFieldName(): string
	{
		return $this->fieldName;
	}

	/**
	 * @param string $message
	 * @return void
	 */
	protected function setErrorMessageForField(string $message): void
	{
		$formErrorSessionMessages = Session::get(FormConstants::FORM_ERROR_MESSAGE_SESSION_NAME) ?? [];
		Session::set(FormConstants::FORM_ERROR_MESSAGE_SESSION_NAME, array_merge($formErrorSessionMessages, [$this->getFieldName() => $message]));
	}
}