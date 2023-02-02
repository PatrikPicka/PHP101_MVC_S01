<?php

namespace App\Models;

use Core\Superclasses\BaseModel;
use DateTime;

class ContentCategory extends BaseModel
{
	public string $name;
	public string $description;
	public string $slug;
	public DateTime $createdAt;
	public DateTime $updatedAt;
	public ?DateTime $deletedAt;

	protected const TABLE = 'content_category';

	public function __construct(?string $slug = null)
	{
		parent::__construct(table: self::TABLE);

		if ($slug !== null) {
			$this->getObjectByConditions(['slug' => $slug]);
			$this->setObjectVariables();
		}
	}
}