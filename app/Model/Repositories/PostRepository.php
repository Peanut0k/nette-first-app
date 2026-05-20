<?php
namespace App\Model\Repositories;

use Nette;
use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

class PostRepository extends BaseRepository
{
  protected string $tableName = 'posts';


}