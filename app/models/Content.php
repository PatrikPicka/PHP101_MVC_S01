<?php

namespace App\Models;

use Core\Superclasses\BaseModel;
use DateTime;

class Content extends BaseModel
{
	public int $contentCategoryId;
	public ?int $previousContentId = null;
	public string $title;
	public string $description;
	public string $videoIdentifier;
	public DateTime $createdAt;
	public DateTime $updatedAt;
	public ?DateTime $deletedAt = null;

	protected const TABLE = 'content';

	public function __construct(?int $id = null)
	{
		parent::__construct(table: self::TABLE, id: $id);
	}

	/**
	 * @param string $slug
	 * @return array|null
	 * @throws \Exception
	 */
	public function findAllContentForCategoryBySlug(string $slug): ?array
	{
		$selectSql = '
			SELECT c.id, c.content_category_id, c.previous_content_id, c.title, c.description, c.video_identifier, c.created_at, c.updated_at, c.deleted_at
			FROM ' . self::TABLE . ' as c
			LEFT JOIN content_category as cc ON c.content_category_id = cc.id
			WHERE cc.slug = :slug AND c.deleted_at IS NULL
		';
		if (!$this->getDb()->query($selectSql, ['slug' => $slug])->error()) {
			$contents = [];
			foreach ($this->getDb()->results() as $contentData) {
				$content = new Content();
				$content->setObject($contentData);
				$content->setObjectVariables();

				$contents[] = $content;
			}

			return !empty($contents) ? $contents : null;
		}

		return null;
	}

	/**
	 * @return ContentCategory
	 */
	public function getCategory(): ContentCategory
	{
		return new ContentCategory(data: $this->contentCategoryId);
	}
}