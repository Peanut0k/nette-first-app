<?php

declare(strict_types=1);

namespace App\Model\Repositories;

use App\Model\DTO\RoleData;
use App\Model\DTO\RoleMapper;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final class RoleRepository extends BaseRepository
{
	public function __construct(Explorer $database)
	{
		parent::__construct($database);
	}

	public function getByName(string $name): ?RoleData
	{
		$row = $this->database->table('roles')
			->where('name', $name)
			->fetch();

		return $row !== null ? RoleMapper::fromRow($row) : null;
	}

	public function getById(int $id): ?RoleData
	{
		$row = $this->database->table('roles')
			->where('id', $id)
			->fetch();

		return $row !== null ? RoleMapper::fromRow($row) : null;
	}

	public function getAll(): array
	{
		$rows = $this->database->table('roles')
			->fetchAll();

		return array_map(
			fn(ActiveRow $row) => RoleMapper::fromRow($row),
			iterator_to_array($rows),
		);
	}
}
