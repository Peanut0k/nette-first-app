<?php
namespace App\Model\Repositories;

use App\Model\DTO\PostMapper;
use Nette;
use App\Model\DTO\PostData;

class PostRepository extends BaseRepository
{
 protected string $tableName = 'posts';

  public function __construct(
      Nette\Database\Explorer $database,
      private PostMapper $postMapper,
  ) {
      parent::__construct($database);
  }

  public function getPostById(int $id): ?PostData
  {
    $row = $this->findById($id);

    if (!$row) {
      return null;
    }

    return $this->postMapper->mapRow($row);
  }

  public function getAllPosts(): array
  {
    $rows = $this->findAll();

    return $this->postMapper->mapCollection($rows);
  }

}