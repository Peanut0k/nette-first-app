<?php

namespace App\Module\Front\Presenters;

use Nette;
use App\Model\Repositories\UserRepository;
use App\Model\Repositories\RoleRepository;
use App\Model\Services\AuthorizationService;

final class AdminPresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private UserRepository $userRepository,
		private RoleRepository $roleRepository,
		private AuthorizationService $authorizationService,
	) {
	}

	public function startup(): void
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Login:in');
		}

		if (!$this->authorizationService->isAdmin($this->getUser())) {
			$this->error('Nemáte oprávnění přistupovat na admin panel.', Nette\Http\IResponse::S403_FORBIDDEN);
		}
	}

	public function renderDefault(): void
	{
		$users = $this->userRepository->getAllWithRoles();
		$roles = $this->roleRepository->getAll();

		foreach ($users as &$user) {
			$user['currentRole'] = array_filter($roles, fn($role) => $role->id === $user['role_id'])[0] ?? null;
		}

		$this->template->users = $users;
		$this->template->roles = $roles;
	}

	public function handleUpdateRole(int $userId, int $roleId): void
	{
		if (!$this->authorizationService->isAdmin($this->getUser())) {
			$this->error('Nemáte oprávnění aktualizovat role.', Nette\Http\IResponse::S403_FORBIDDEN);
		}

		$role = $this->roleRepository->getById($roleId);
		if (!$role) {
			$this->error('Role nebyla nalezena.');
		}

		try {
			$this->userRepository->updateById($userId, ['role_id' => $roleId]);
			$this->flashMessage('Role uživatele byla úspěšně aktualizována.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Nepodařilo se aktualizovat roli: ' . $e->getMessage(), 'danger');
		}

		$this->redirect('this');
	}
}
