<?php

declare(strict_types=1);

namespace App\Model\Repositories;

use Nette;

abstract class BaseRepository
{
	protected string $tableName;

	public function __construct(protected Nette\Database\Explorer $database,) {
	}

	protected function getTable(): Nette\Database\Table\Selection
	{
		return $this->database->table($this->tableName);
	}

	public function findAll(): Nette\Database\Table\Selection
	{
		return $this->getTable();
	}

	public function findById(int $id): ?Nette\Database\Table\ActiveRow
	{
		return $this->getTable()->get($id);
	}

  public function deleteById(int $id): void
  {
    $this->getTable()->wherePrimary($id)->delete();
  }

  public function updateById(int $id, array $data): void
  {
    $this->getTable()->wherePrimary($id)->update($data);
  }

  public function create(array $data): Nette\Database\Table\ActiveRow
  {
    return $this->getTable()->insert($data);
  }
}