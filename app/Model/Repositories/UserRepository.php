<?php
namespace App\Model\Repositories;

use Nette;
use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

class UserRepository extends BaseRepository
{
  protected string $tableName = 'users';

  public function findByUsername(string $username): ?Nette\Database\Table\ActiveRow
  {
    return $this->getTable()->where('username', $username)->fetch();
  }

  public function getAllWithRoles(): array
  {
    $rows = $this->findAll()->order('created_at DESC')->fetchAll();
    $result = [];
    foreach ($rows as $row) {
      $result[] = [
        'id' => $row->id,
        'username' => $row->username,
        'role_id' => $row->role_id,
        'created_at' => $row->created_at,
      ];
    }
    return $result;
  }
}
