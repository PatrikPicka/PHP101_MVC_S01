<?php

namespace App\Models;

use Core\Superclasses\BaseModel;
use Couchbase\PathNotFoundException;
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
	 * @param string $email
	 * @return bool
	 * @throws PathNotFoundException
	 */
	public function userLoadedByEmail(string $email): bool
	{
		$this->getObjectByConditions(conditions: ['email' => $email]);
		if ($this->object !== null) {
			$this->setObjectVariables();

			return true;
		}

		return false;
	}

	/**
	 * @param Content $content
	 * @return bool
	 */
	public function hasAccessToContent(Content $content): bool
	{
		if (self::isUserLoggedIn()) {
			if ($content->previousContentId === null) {
				return true;
			}

			$sql = '
				SELECT ul.finished FROM user_content AS ul
				WHERE ul.user_id = :user_id AND ul.content_id = :content_id
			';
			if (!$this->getDb()->query($sql, ['user_id' => self::getLoggedInUserId(), 'content_id' => $content->previousContentId])->error()) {
				$data = $this->getDb()->getFirstResult();
				$finished = $data !== null ? $data->finished : 0;

				return $finished === 1;
			}
		}

		return false;
	}

	/**
	 * @param int $contentId
	 * @return bool
	 */
	public function createLectureIfNotExists(int $contentId): bool
	{
		$selectSql = 'SELECT user_id FROM user_content WHERE user_id = :user_id AND content_id = :content_id';

		if (!$this->getDb()->query($selectSql, ['user_id' => $this->getId(), 'content_id' => $contentId], 1)->error() && $this->getDb()->count() === 0) {
			$insertSql = 'INSERT INTO user_content (user_id, content_id) VALUES (:user_id, :content_id)';
			if (!$this->getDb()->query($insertSql, ['user_id' => $this->getId(), 'content_id' => $contentId])->error()) {
				return true;
			}
		} elseif ($this->getDb()->error()) {
			return false;
		}

		return true;
	}

	/**
	 * @param int $contentId
	 * @param int $finished
	 * @return bool
	 */
	public function updateLectureToFinished(int $contentId, int $finished): bool
	{
		$sql = 'UPDATE user_content SET finished = :finished WHERE user_id = :user_id AND content_id = :content_id';

		if (!$this->getDb()->query($sql, ['finished' => 1, 'user_id' => $this->getId(), 'content_id' => $contentId])->error()) {
			return true;
		}

		return false;
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