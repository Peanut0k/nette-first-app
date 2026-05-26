<?php
namespace App\Module\Front\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Repositories\CommentRepository;
use App\Model\Repositories\PostRepository;
use App\Model\Facades\PostFacade;
use App\Model\Services\AuthorizationService;

final class PostPresenter extends Nette\Application\UI\Presenter {
  public function __construct(
	private CommentRepository $commentRepository,
	private PostRepository $postRepository,
	private PostFacade $postFacade,
	private AuthorizationService $authorizationService,
  ) {

  }

 public function renderShow(int $id): void
{
	$post = $this->postRepository->getPostById($id);
	$this->template->post = $post;
	$this->template->comments = $this->postFacade->findCommentsByPostId($id);

	// Pass authorization methods to template
	$this->template->user = $this->getUser();
	$this->template->authService = $this->authorizationService;

	$editCommentId = (int) $this->getParameter('editCommentId');
	$this->template->editCommentId = $editCommentId;

	if ($editCommentId > 0) {
		$comment = $this->commentRepository->findById($editCommentId);
		if ($comment) {
			$this->getComponent('editCommentForm')->setDefaults([
				'id' => $comment->id,
				'name' => $comment->name,
				'content' => $comment->content,
			]);
		}
	}
}
 public function createComponentCommentForm(): Form
{
  $form = new Form;

  $form->addHidden('name')
    ->setValue($this->getUser()->getIdentity()->username);

  $form->addTextArea('content', 'komentář')
    ->setRequired('Zadejte komentář.');

  $form->addSubmit('submit', 'Odeslat');

  $form->onSuccess[] = [$this, 'postFormSucceeded'];
  return $form;
}
 public function createComponentEditCommentForm(): Form
{
  $form = new Form;
  $form->addHidden('id')
    ->setRequired();

  $form->addHidden('name')
    ->setValue($this->getUser()->getIdentity()->username);

  $form->addTextArea('content', 'Komentář')
    ->setRequired('Zadejte komentář.');

  $form->addSubmit('save', 'Uložit komentář');
  $form->onSuccess[] = [$this, 'editCommentSucceeded'];

  return $form;
}
 public function postFormSucceeded(Form $form): void
{
	if (!$this->authorizationService->canCreateComment($this->getUser())) {
		$this->error('Nemáte oprávnění přidávat komentáře.', Nette\Http\IResponse::S403_FORBIDDEN);
	}

    $data = $form->getValues('array');
    
    try {
        $this->postFacade->addCommentToPost(
			(int) $this->getParameter('id'),
			$data,
			$this->getUser()->isLoggedIn() ? $this->getUser()->getId() : null,
		);
        $this->flashMessage('Komentář byl úspěšně přidán.');
    } catch (\Exception $e) {
        $this->flashMessage('Nepodařilo se přidat komentář: ' . $e->getMessage(), 'danger');
    }
    
    $this->redirect('this');
}
 public function editCommentSucceeded(Form $form): void
{
    $data = $form->getValues('array');
    $commentId = (int) $data['id'];

    $comment = $this->commentRepository->findById($commentId);
    if (!$comment) {
        $this->error('Komentář nebyl nalezen');
    }

	if (!$this->authorizationService->canEditComment($this->getUser(), $comment)) {
		$this->error('Nemáte oprávnění upravovat tento komentář.', Nette\Http\IResponse::S403_FORBIDDEN);
	}

    $this->commentRepository->updateById($commentId, [
        'name' => $data['name'],
        'content' => $data['content'],
    ]);

    $this->flashMessage('Komentář byl upraven.', 'success');
    $this->redirect('this', ['editCommentId' => null]);
}
 public function handleDeleteComment(int $commentId): void
{
    $comment = $this->commentRepository->findById($commentId);
    if (!$comment) {
        $this->error('Komentář nebyl nalezen');
    }

	if (!$this->authorizationService->canDeleteComment($this->getUser(), $comment)) {
		$this->error('Nemáte oprávnění mazat tento komentář.', Nette\Http\IResponse::S403_FORBIDDEN);
	}

    try {
        $this->postFacade->deleteCommentById($commentId);
        $this->flashMessage('Komentář byl úspěšně smazán.', 'success');
    } catch (\Exception $e) {
        $this->flashMessage('Nepodařilo se smazat komentář. ' . $e->getMessage(), 'danger');
    }

    $this->redirect('this');
}
}