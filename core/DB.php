<?php

namespace Core;

use Core\Constants\DBConstants;
use DateTime;
use PDO;
use PDOException;
use PDOStatement;

class DB
{
	private static DB $instance;
	private PDO $pdo;
	private PDOStatement $query;
	private bool $error = false;
	private array $results = [];
	private int $count = 0;
	private ?int $lastInsertId = null;

	protected string $table;

	private function __construct()
	{
		try {
			$this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
		} catch (PDOException $exception) {
			DEBUG ? die($exception->getMessage()) : Router::route(['error', 'databaseConnectionFailed']);
		}
	}

	/**
	 * @return DB
	 */
	public static function getInstance(): DB
	{
		if (!isset($instance)) {
			self::$instance = new DB();
		}

		return self::$instance;
	}

	/**
	 * @param string $sql
	 * @param array $params
	 * @param int|null $limit
	 * @param int|null $offset
	 * @param string|null $orderBy
	 * @return $this
	 */
	public function query(string $sql, array $params = [], ?int $limit = null, ?int $offset = null, ?string $orderBy = null)
	{
		$this->error = false;

		if ($limit !== null) {
			$sql .= " LIMIT = $limit";
		}

		if ($offset !== null) {
			$sql .= " OFFSET = $offset";
		}

		if ($orderBy !== null) {
			$sql .= " ORDER BY = $orderBy";
		}

		if ($this->query = $this->pdo->prepare($sql)) {
			if (count($params) > 0) {
				foreach ($params as $key => $param) {
					$this->query->bindParam($key, $param);
				}
			}
		}

		if ($this->query->execute()) {
			$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
			$this->count = $this->query->rowCount();
			$this->lastInsertId = $this->pdo->lastInsertId();
		} else {
			$this->error = true;
		}

		return $this;
	}

	/**
	 * @param array $params
	 * @return bool
	 */
	public function insert(array $params): bool
	{
		$fieldString = '';
		$valueString = '';
		foreach ($params as $field => $value) {
			$fieldString .= '`' . $field . '`, ';
			$valueString .= ':' . $field . ', ';
		}

		$fieldString = rtrim($fieldString, ', ');
		$valueString = rtrim($valueString, ', ');

		$sql = "INSERT INTO $this->table ($fieldString) VALUES ($valueString)";

		return $this->runQueryAndCheckForErrors(sql: $sql, params: $params);
	}

	/**
	 * @param int $id
	 * @param $params
	 * @return bool
	 */
	public function update(int $id, array $params): bool
	{
		$updateFieldsString = '';
		foreach (array_keys($params) as $field) {
			$updateFieldsString .= "`$field` = :$field ";
		}

		$params['id'] = $id;
		$sql = "UPDATE $this->table SET $updateFieldsString WHERE id = :id";

		return $this->runQueryAndCheckForErrors(sql: $sql, params: $params);
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function delete(int $id): bool
	{
		$params = ['id' => $id];
		if (SOFT_DELETE && $this->isSoftDeletable()) {
			$deletedAtColumName = DBConstants::DB_DELETED_AT_COLUMN_NAME;
			$sql = "UPDATE $this->table SET $deletedAtColumName = :dateTime WHERE id = :id";
			$params['dateTime'] = new DateTime();
		} else {
			$sql = "DELETE FROM $this->table WHERE id = :id";
		}

		return $this->runQueryAndCheckForErrors(sql: $sql, params: $params);
	}

	/**
	 * @return array
	 */
	public function results(): array
	{
		return $this->results;
	}

	/**
	 * @return object|null
	 */
	public function getFirstResult(): ?object
	{
		return !empty($this->results) ? $this->results[array_key_first($this->results)] : null;
	}

	/**
	 * @return int
	 */
	public function count(): int
	{
		return $this->count;
	}

	/**
	 * @return int|null
	 */
	public function lastId(): ?int
	{
		return $this->lastInsertId;
	}

	/**
	 * @param string $table
	 * @return $this
	 */
	public function setTable(string $table): DB
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getColumns(): array
	{
		return $this->query("SHOW COLUMNS FOR $this->table")->results();
	}

	/**
	 * @param string $sql
	 * @param array $params
	 * @return bool
	 */
	public function runQueryAndCheckForErrors(string $sql, array $params): bool
	{
		if (!$this->query(sql: $sql, params: $params)->error()) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function error(): bool
	{
		return $this->error;
	}

	/**
	 * @return bool
	 */
	private function isSoftDeletable(): bool
	{
		$columns = $this->getColumns();

		foreach ($columns as $column) {
			if ($columns->Field === DBConstants::DB_DELETED_AT_COLUMN_NAME) {
				return true;
			}
		}

		return false;
	}

}