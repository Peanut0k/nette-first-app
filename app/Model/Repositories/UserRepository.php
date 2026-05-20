<?php
namespace App\Model\Repositories;

use Nette;
use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

class UserRepository extends BaseRepository
{
  protected string $tableName = 'users';
}