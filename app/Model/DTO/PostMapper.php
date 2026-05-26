<?php
namespace App\Model\DTO;

use App\Model\DTO\PostData;
use Nette\Database\Table\ActiveRow;
use DateTimeImmutable;

class PostMapper
{
    public function mapRow(ActiveRow $row): PostData
    {
        return new PostData(
            id: $row->id,
            user_id: $row->user_id,
            title: $row->title,
            content: $row->content,
            created_at: DateTimeImmutable::createFromInterface($row->created_at),
        );
    }
    public function mapCollection(iterable $rows): array
	{
		$dtos = [];
		foreach ($rows as $row) {
			$dtos[] = $this->mapRow($row);
		}
		return $dtos;
	}
}