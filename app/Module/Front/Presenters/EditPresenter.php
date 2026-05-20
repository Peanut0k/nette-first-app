<?php
namespace App\Module\Front\Presenters;
use Nette;
use Nette\Application\UI\Form;
use App\Model\Repositories\CommentRepository;
use App\Model\Repositories\PostRepository;

final class EditPresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private PostRepository $postRepository,
		private CommentRepository $commentRepository,
	) {
	}

	public function startup(): void
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}

	public function renderEdit(int $id): void
	{

		$post = $this->postRepository->findById($id);

		if (!$post) {
			$this->error('Příspěvek nebyl nalezen');
		}
		$this->getComponent('postForm')->setDefaults($post->toArray());
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
		$data = $form->getValues();
		$id = (int) $this->getParameter('id');

		if ($id) {
			$post = $this->postRepository->findById($id);
			if (!$post) {
				$this->error('Příspěvek nebyl nalezen');
			}
			$post->update($data);

		} else {
			$post = $this->postRepository->create([
				'title' => $data->title,
				'content' => $data->content,
			]);
		}

		$this->flashMessage('Příspěvek byl úspěšně publikován.', 'success');
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
		$data = $form->getValues();
		$postId = (int) $data['id'];

		$post = $this->postRepository->findById($postId);
		if (!$post) {
			$this->error('Příspěvek nebyl nalezen');
		}

		try {
			$this->commentRepository->deleteByPostId($postId);
			$this->postRepository->deleteById($postId);
			$this->flashMessage('Příspěvek byl úspěšně smazán.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Nepodařilo se smazat příspěvek. ' . $e->getMessage(), 'danger');
		}

		$this->redirect('Homepage:default');
	}
}