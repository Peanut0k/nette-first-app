<?php
namespace App\Model\Facades;

use Nette;
use App\Model\Repositories\PostRepository;
use App\Model\Repositories\CommentRepository;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final class PostFacade
{
	public function __construct(
		private PostRepository $postRepository,
		private CommentRepository $commentRepository,
		private Explorer $database,
	) {
	}

	public function getPublicArticles(): Nette\Database\Table\Selection
	{
		return $this->postRepository->findAll()
			->where('created_at < ', new \DateTime)
			->order('created_at DESC');
	}

	public function deletePostWithComments(int $postId): void
	{
		$this->database->transaction(function () use ($postId) {
			$this->commentRepository->deleteByPostId($postId);
			$this->postRepository->deleteById($postId);
		});
	}

	public function addCommentToPost(int $postId, array $data, ?int $userId = null): ActiveRow
	{
		return $this->database->transaction(function () use ($postId, $data, $userId) {
			$post = $this->postRepository->findById($postId);
			if (!$post) {
				throw new \Exception('Příspěvek nebyl nalezen');
			}

			return $this->commentRepository->create([
				'post_id' => $postId,
				'user_id' => $userId,
				'name' => $data['name'] ?? '',
				'email' => $data['email'] ?? 'unknown@example.com',
				'content' => $data['content'] ?? '',
			]);
		});
	}

	public function findCommentsByPostId(int $postId): Nette\Database\Table\Selection
	{
		return $this->commentRepository->findByPostId($postId);
	}

	public function deleteCommentById(int $commentId): void
	{
		$this->commentRepository->deleteById($commentId);
	}
}