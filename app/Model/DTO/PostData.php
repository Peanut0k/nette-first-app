<?php

declare(strict_types=1);

namespace App\Model\DTO;

use DateTimeImmutable;

class PostData
{
	public function __construct(
		public int $id,
		public ?int $user_id,
		public string $title,
		public string $content,
		public DateTimeImmutable $created_at,
	) {
	}
}