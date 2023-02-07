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

	public function __construct(null|int|string $data)
	{
		if ($data !== null && filter_var($data, FILTER_VALIDATE_INT)) {
			parent::__construct(table: self::TABLE, id: $data);
		} else {
			parent::__construct(table: self::TABLE);

			if ($data !== null) {
				$this->getObjectByConditions(['slug' => $data]);
				$this->setObjectVariables();
			}
		}
	}
}