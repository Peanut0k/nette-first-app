<?php
namespace App\Module\Front\Presenters;
use Nette;
use Nette\Application\UI\Form;
use App\Model\Repositories\CommentRepository;
use App\Model\Repositories\PostRepository;
use App\Model\Facades\PostFacade;
use App\Model\Services\AuthorizationService;

final class EditPresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private PostRepository $postRepository,
		private CommentRepository $commentRepository,
		private PostFacade $postFacade,
		private AuthorizationService $authorizationService,
	) {
	}

	public function startup(): void
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Login:in');
		}
	}

	public function actionCreate(): void
	{
		if (!$this->authorizationService->canCreatePost($this->getUser())) {
			$this->error('Nemáte oprávnění vytvářet příspěvky.', Nette\Http\IResponse::S403_FORBIDDEN);
		}
	}

	public function actionEdit(int $id): void
	{
		$post = $this->postRepository->getPostById($id);

		if (!$post) {
			$this->error('Příspěvek nebyl nalezen');
		}

		if (!$this->authorizationService->canEditPost($this->getUser(), $post)) {
			$this->error('Nemáte oprávnění upravovat tento příspěvek.', Nette\Http\IResponse::S403_FORBIDDEN);
		}
	}

	public function actionDelete(int $id): void
	{
		$post = $this->postRepository->getPostById($id);

		if (!$post) {
			$this->error('Příspěvek nebyl nalezen');
		}

		if (!$this->authorizationService->canDeletePost($this->getUser(), $post)) {
			$this->error('Nemáte oprávnění mazat tento příspěvek.', Nette\Http\IResponse::S403_FORBIDDEN);
		}
	}

	public function renderEdit(int $id): void
	{

		$post = $this->postRepository->getPostById($id);

		if (!$post) {
			$this->error('Příspěvek nebyl nalezen');
		}
		$this->getComponent('postForm')->setDefaults([
			'title' => $post->title,
			'content' => $post->content,
		]);
		$this->getComponent('deleteForm')->setDefaults(['id' => $post->id]);
	}

	protected function createComponentPostForm(): Form
	{
		$form = new Form;
		$form->addText('title', 'Titulek:')
			->setRequired();
		$form->addTextArea('content', 'Obsah:')
			->setRequired();

		$form->addSubmit('send', 'Uložit a publikovat');

		$form->onSuccess[] = [$this, 'postFormSucceeded'];

		return $form;
	}

	public function postFormSucceeded(Form $form): void
	{
	$data = $form->getValues('array');
	$id = (int) $this->getParameter('id');

	if ($id > 0) {
		$data['id'] = $id;
	} else {
		// For new posts, set the user_id
		$data['user_id'] = $this->getUser()->getId();
	}

	$post = $this->postRepository->save($data);
	$this->flashMessage('Příspěvek byl úspěšně uložen.', 'success');
		$this->redirect('Post:show', $post->id);
	}

	protected function createComponentDeleteForm(): Form
	{
		$form = new Form;

		$form->addHidden('id')
			->setRequired();

		$form->addSubmit('delete', 'Smazat příspěvek');

		$form->onSuccess[] = [$this, 'deletePostSucceeded'];

		return $form;
	}

	public function deletePostSucceeded(Form $form): void
	{
		$data = $form->getValues('array');
		$postId = (int) $data['id'];

		$post = $this->postRepository->getPostById($postId);
		if (!$post) {
			$this->error('Příspěvek nebyl nalezen');
		}

		try {
			$this->postFacade->deletePostWithComments($postId);
			$this->flashMessage('Příspěvek byl úspěšně smazán.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Nepodařilo se smazat příspěvek. ' . $e->getMessage(), 'danger');
		}

		$this->redirect('Homepage:default');
	}
}