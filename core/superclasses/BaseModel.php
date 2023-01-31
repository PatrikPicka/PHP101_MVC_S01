<?php

namespace Core\Superclasses;

use Core\Constants\DBConstants;
use Core\DB;
use Core\Helpers\StringHelpers;
use Core\Router;
use Couchbase\PathNotFoundException;
use DateTime;

abstract class BaseModel
{
	protected DB $db;
	protected string $table;
	protected array $columns;
	protected ?object $object = null;
	protected ?int $id = null;

	public function __construct(string $table, ?int $id = null)
	{
		$this->db = DB::getInstance();
		$this->db->setTable($table);
		$this->table = $table;
		$this->columns = $this->db->getColumns();

		if ($id !== null) {
			$this->id = $id;
			$this->getObjectByConditions(conditions: ['id' => $id]);
			$this->setObjectVariables();
		}
	}

	/**
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function populate(): void
	{
		$params = [];
		foreach ($this->columns as $column) {
			if ($column->Field !== 'id'
				&& $column->Field !== DBConstants::DB_CREATED_AT_COLUMN_NAME
				&& $column->Field !== DBConstants::DB_UPDATED_AT_COLUMN_NAME
				&& $column->Field !== DBConstants::DB_DELETED_AT_COLUMN_NAME
			) {
				$params[$column->Field] = $this->{StringHelpers::snakeCaseToCamelCase($column->Field)};
			}
		}

		$dateTime = new DateTime();
		$dateTimeFormatted = $dateTime->format('Y-m-d H:i:s');

		if ($this->hasLifeCicleCallbackColumns()) {
			$params[DBConstants::DB_CREATED_AT_COLUMN_NAME] = $dateTimeFormatted;
			$params[DBConstants::DB_UPDATED_AT_COLUMN_NAME] = $dateTimeFormatted;
		}

		if ($this->object === null && $this->id === null) {
			$this->db->insert(params: $params);
			$this->id = $this->db->lastId();
			$this->getObjectByConditions(conditions: ['id' => $this->id]);
			$this->setObjectVariables();
		} else {
			unset($params[DBConstants::DB_CREATED_AT_COLUMN_NAME]);
			$this->db->update(id: $this->getId(), params: $params);
		}

		if ($this->db->error()) {
			DEBUG ? die('There was and error saving data for: ' . get_class($this) . ' With id: #' . $this->getId()) : Router::route(['error', 'internalServerError']);
		}
	}

	/**
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function delete(): void
	{
		$this->db->delete($this->getId());

		if ($this->db->error()) {
			DEBUG ? die('There was and error deleting: ' . get_class($this) . ' With id: #' . $this->getId()) : Router::route(['error', 'internalServerError']);
		}
	}

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return DB
	 */
	public function getDb(): DB
	{
		return $this->db;
	}

	/**
	 * @param int $id
	 * @return void
	 * @throws PathNotFoundException
	 */
	protected function getObjectByConditions(array $conditions): void
	{
		if ($this->db->select(['*'], $conditions, 1)->error()) {
			DEBUG ? die('There was an error while trying to fetch objects data: ' . get_class($this) . ' With id: #' . $id) : Router::route(['error', 'internalServerError']);
		}

		$this->object = $this->db->getFirstResult();
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	protected function setObjectVariables(): void
	{
		foreach ($this->columns as $column) {
			$columnName = StringHelpers::snakeCaseToCamelCase($column->Field);
			if ($column->Type === DBConstants::DB_DATE_TIME_COLUMN_TYPE && property_exists($this, $columnName)) {
				$this->{$columnName} = $this->object->{$column->Field} !== null ? new DateTime($this->object->{$column->Field}) : null;
			} elseif (property_exists($this, $columnName)) {
				$this->{$columnName} = $this->object->{$column->Field};
			}
		}
	}

	/**
	 * @return bool
	 */
	protected function hasLifeCicleCallbackColumns(): bool
	{
		$columnsArray = [];
		foreach ($this->columns as $column) {
			$columnsArray[] = $column->Field;
		}

		return in_array(DBConstants::DB_CREATED_AT_COLUMN_NAME, $columnsArray) && in_array(DBConstants::DB_UPDATED_AT_COLUMN_NAME, $columnsArray);
	}
}