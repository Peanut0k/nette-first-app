<?php

declare(strict_types=1);

namespace App\Model\Services;

use App\Model\DTO\PostData;
use App\Model\DTO\RoleData;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;

final class AuthorizationService
{
	public function __construct()
	{
	}

	/**
	 * Posts
	 */

	public function canViewPost(?User $user): bool
	{
		// Everyone can view posts (logged in or not)
		return true;
	}

	public function canCreatePost(?User $user): bool
	{
		// Only logged-in users with Author or Admin role
		if ($user === null || !$user->isLoggedIn()) {
			return false;
		}

		$role = $user->getIdentity()?->role;
		if (!$role instanceof RoleData) {
			return false;
		}

		return $role->isAuthor() || $role->isAdmin();
	}

	public function canEditPost(?User $user, ?PostData $post): bool
	{
		// Only the post author or admin can edit
		if ($user === null || !$user->isLoggedIn() || $post === null) {
			return false;
		}

		$role = $user->getIdentity()?->role;
		if (!$role instanceof RoleData) {
			return false;
		}

		if ($role->isAdmin()) {
			return true;
		}

		// Author can edit own post
		if ($role->isAuthor() && $post->user_id === $user->getId()) {
			return true;
		}

		return false;
	}

	public function canDeletePost(?User $user, ?PostData $post): bool
	{
		return $this->canEditPost($user, $post);
	}

	/**
	 * Comments
	 */

	public function canViewComments(?User $user): bool
	{
		// Everyone can view comments
		return true;
	}

	public function canCreateComment(?User $user): bool
	{
		// Only logged-in users with User or higher role
		if ($user === null || !$user->isLoggedIn()) {
			return false;
		}

		$role = $user->getIdentity()?->role;
		if (!$role instanceof RoleData) {
			return false;
		}

		return $role->isUser() || $role->isAuthor() || $role->isAdmin();
	}

	public function canEditComment(?User $user, ?ActiveRow $comment): bool
	{
		// Only the comment author or admin can edit
		if ($user === null || !$user->isLoggedIn() || $comment === null) {
			return false;
		}

		$role = $user->getIdentity()?->role;
		if (!$role instanceof RoleData) {
			return false;
		}

		// Admin can edit any comment
		if ($role->isAdmin()) {
			return true;
		}

		// User can edit own comment
		if (($role->isUser() || $role->isAuthor()) && $comment->user_id === $user->getId()) {
			return true;
		}

		return false;
	}

	public function canDeleteComment(?User $user, ?ActiveRow $comment): bool
	{
		// Same as edit
		return $this->canEditComment($user, $comment);
	}

	public function isAdmin(?User $user): bool
	{
		if ($user === null || !$user->isLoggedIn()) {
			return false;
		}

		$role = $user->getIdentity()?->role;
		if (!$role instanceof RoleData) {
			return false;
		}

		return $role->isAdmin();
	}
}
