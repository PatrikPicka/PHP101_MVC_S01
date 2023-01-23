<?php

namespace App\Models;

use Core\Superclasses\BaseModel;
use DateTime;

class User extends BaseModel
{
	public string $username;
	public string $email;
	public string $password;
	public DateTime $createdAt;
	public DateTime $updatedAt;
	public ?DateTime $deletedAt;
	public string $acls = '["user"]';

	protected const TABLE = 'user';

	public function __construct(?int $id = null)
	{
		parent::__construct(table: self::TABLE, id: $id);
	}

	/**
	 * @return array
	 */
	public function getAcls(): array
	{
		return json_decode($this->acls);
	}

	/**
	 * @return bool
	 */
	public static function isUserLoggedIn(): bool
	{
		return isset($_SESSION[LOGGED_IN_USER_SESSION]);
	}

	/**
	 * @return int|null
	 */
	public static function getLoggedInUserId(): ?int
	{
		if (self::isUserLoggedIn()) {
			return $_SESSION[LOGGED_IN_USER_SESSION];
		}

		return null;
	}
}