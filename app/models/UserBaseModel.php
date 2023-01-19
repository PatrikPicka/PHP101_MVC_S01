<?php

namespace App\Models;

use Core\Superclasses\BaseModel;
use DateTime;

class UserBaseModel extends BaseModel
{
	public string $username;
	public string $email;
	public string $password;
	public DateTime $createdAt;
	public DateTime $updatedAt;
	public ?DateTime $deletedAt;

	protected const TABLE = 'user';

	public function __construct(?int $id = null)
	{
		parent::__construct(table: self::TABLE, id: $id);
	}
}