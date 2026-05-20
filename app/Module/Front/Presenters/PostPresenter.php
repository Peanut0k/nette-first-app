<?php
namespace App\Module\Front\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Repositories\CommentRepository;
use App\Model\Repositories\PostRepository;

final class PostPresenter extends Nette\Application\UI\Presenter {
  public function __construct(private CommentRepository $commentRepository, private PostRepository $postRepository) {

  }

 public function renderShow(int $id): void
{
	
  $post = $this->postRepository->findById($id);

	$this->template->post = $post;
	$this->template->comments = $this->commentRepository->findByPostId($id);
}
 public function createComponentCommentForm(): Form
{
  $form = new Form;
  $form->addText('name', 'Jméno')
    ->setRequired('Zadejte své jméno.');

  $form->addTextArea('content', 'komentář')
    ->setRequired('Zadejte komentář.');

  $form->addSubmit('submit', 'Odeslat');

  $form->onSuccess[] = [$this, 'postFormSucceeded'];
  return $form;
}
 public function postFormSucceeded(Form $form): void
{
    $data = $form->getValues();
    $this->commentRepository->create(
        [
            'post_id' => (int) $this->getParameter('id'),
            'name' => $data->name,
            'email' => "a@a.a",
            'content' => $data->content
        ]
    );
    $this->redirect('this');
}
}