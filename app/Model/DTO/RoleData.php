<?php

declare(strict_types=1);

namespace App\Model\DTO;

final class RoleData
{
	public const GUEST = 'guest';
	public const USER = 'user';
	public const AUTHOR = 'author';
	public const ADMIN = 'admin';

	public function __construct(
		public readonly int $id,
		public readonly string $name,
		public readonly ?string $description = null,
	) {
	}

	public function isAdmin(): bool
	{
		return $this->name === self::ADMIN;
	}

	public function isAuthor(): bool
	{
		return $this->name === self::AUTHOR;
	}

	public function isUser(): bool
	{
		return $this->name === self::USER;
	}

	public function isGuest(): bool
	{
		return $this->name === self::GUEST;
	}
}
