<?php
namespace App\Model\Repositories;

use Nette;

class CommentRepository extends BaseRepository
{
  protected string $tableName = 'comments';

  public function findByPostId(int $postId): Nette\Database\Table\Selection
  {
    return $this->getTable()->where('post_id', $postId);
  }

  public function deleteByPostId(int $postId): void
  {
    $this->getTable()->where('post_id', $postId)->delete();
  }
}