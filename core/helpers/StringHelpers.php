<?php

namespace Core\Helpers;

class StringHelpers
{
	public static function snakeCaseToCamelCase(string $string): string
	{
		return lcfirst(str_replace('_', '', ucwords($string, '_')));
	}
}