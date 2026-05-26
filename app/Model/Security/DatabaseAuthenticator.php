<?php

declare(strict_types=1);

namespace App\Model\Security;

use App\Model\DTO\RoleData;
use App\Model\Repositories\RoleRepository;
use App\Model\Repositories\UserRepository;
use Nette\Security\Authenticator;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;

final class DatabaseAuthenticator implements Authenticator
{
	public function __construct(
		private UserRepository $userRepository,
		private RoleRepository $roleRepository,
	) {
	}

	public function authenticate(string $username, string $password): \Nette\Security\IIdentity
	{
		$user = $this->userRepository->findByUsername($username);
		if (!$user) {
			throw new AuthenticationException('Uživatel nebyl nalezen.', self::IdentityNotFound);
		}

		if (!password_verify($password, $user->password)) {
			throw new AuthenticationException('Neplatné heslo.', self::InvalidCredential);
		}

		// Load user's role
		$role = $this->roleRepository->getById($user->role_id);
		if ($role === null) {
			throw new AuthenticationException('Roli uživatele nelze načíst.', self::IdentityNotFound);
		}

		// Create identity with role information
		return new Identity(
			$user->id,
			['authenticated'],
			[
				'username' => $user->username,
				'role' => $role,
			],
		);
	}
}
