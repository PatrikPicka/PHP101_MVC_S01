<?php

namespace Core\Helpers;

class AppHelpers
{
	/**
	 * @return string
	 */
	public static function getCurrentPage(): string
	{
		$currentPage = $_SERVER['REQUEST_URI'];
		$defaultControllerBaseName = strtolower(DEFAULT_CONTROLLER_BASE_NAME);

		if (
			$currentPage === PROOT ||
			$currentPage === PROOT . $defaultControllerBaseName ||
			$currentPage === PROOT . $defaultControllerBaseName . '/' . DEFAULT_ACTION_BASE_NAME
		) {
			$currentPage = PROOT . $defaultControllerBaseName;
		}

		return $currentPage;
	}
}