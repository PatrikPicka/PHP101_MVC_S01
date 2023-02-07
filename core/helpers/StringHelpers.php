<?php

namespace Core\Helpers;

class StringHelpers
{
	/**
	 * @param string $string
	 * @return string
	 */
	public static function snakeCaseToCamelCase(string $string): string
	{
		return lcfirst(str_replace('_', '', ucwords($string, '_')));
	}

	/**
	 * @param string $string
	 * @param int $maxLength
	 * @param string $append
	 * @return string
	 */
	public static function getShortenedStringWhenIsLongerThanNeeded(string $string, int $maxLength, string $append = '...'): string
	{
		return strlen($string) > $maxLength ? substr($string, 0, $maxLength) . $append : $string;
	}
}