<?php

namespace Core;

class Session
{
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function exists(string $name): bool
	{
		return isset($_SESSION[$name]);
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public static function get(string $name): mixed
	{
		return $_SESSION[$name] ?? null;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public static function set(string $name, mixed $value): void
	{
		$_SESSION[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public static function delete(string $name): void
	{
		if (self::exists(name: $name)) {
			unset($_SESSION[$name]);
		}
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public static function flash(string $name): mixed
	{
		if (isset($_SESSION[$name])) {
			$session = $_SESSION[$name];
			self::delete(name: $name);

			return $session;
		}

		return null;
	}

	/**
	 * @param string $message
	 * @param string $type
	 * @return void
	 */
	public static function setAlertMessage(string $message, string $type = ALERT_SUCCESS): void
	{
		self::set(name: ALERT_NAME, value: [
			'type' => $type,
			'message' => $message,
		]);
	}
}