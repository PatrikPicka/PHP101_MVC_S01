<?php

namespace Core;

class Cookie
{
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param int $expiry
	 * @return bool
	 */
	public static function set(string $name, mixed $value, int $expiry): bool
	{
		if (setcookie($name, $value, time() + $expiry, '/')) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public static function delete(string $name): void
	{
		self::set(name: $name, value: '', expiry: time() - 1);
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public static function get(string $name): mixed
	{
		return $_COOKIE[$name] ?? null;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public static function exists(string $name): bool
	{
		return isset($_COOKIE[$name]);
	}
}