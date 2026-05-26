<?php

declare(strict_types=1);

namespace App\Model\DTO;

use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

final class RoleMapper
{
	public static function fromRow(ActiveRow $row): RoleData
	{
		return new RoleData(
			id: $row->id,
			name: $row->name,
			description: $row->description,
		);
	}
}
