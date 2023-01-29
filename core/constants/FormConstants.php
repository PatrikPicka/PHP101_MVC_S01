<?php

namespace Core\Constants;

class FormConstants
{
	public const FORM_METHOD_POST = 'POST';
	public const FORM_METHOD_GET = 'GET';

	public const FIELD_LABEL = 'label';

	public const TEXT_TYPE = 'text';

	public const PASSWORD_TYPE = 'password';
	public const PASSWORD_MIN_LENGTH = 4;
	public const PASSWORD_MAX_LENGTH = 25;

	public const EMAIL_TYPE = 'email';

	public const SELECT_TYPE_CHOICES = 'choices';

	public const FORM_ERROR_MESSAGE_SESSION_NAME = 'form_error';
}