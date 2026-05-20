<?php
namespace App\Module\Front\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends Nette\Application\UI\Presenter {
  public function __construct(private Nette\Database\Explorer $database) {

  }

 public function renderShow(int $id): void
{
	$post = $this->database
		->table('posts')
		->get($id);
	if (!$post) {
		$this->error('Stránka nebyla nalezena');
	}
  

	$this->template->post = $post;
	$this->template->comments = $post->related('comments')->order('created_at');
}
 public function createComponentCommentForm(): Form
{
  $form = new Form;
  $form->addText('name', 'Jméno')
    ->setRequired('Zadejte své jméno.');

  $form->addEmail('email', 'Email')
    ->setRequired('Zadejte svůj email.');

  $form->addTextArea('content', 'komentář')
    ->setRequired('Zadejte komentář.');

  $form->addSubmit('submit', 'Odeslat');

  $form->onSuccess[] = [$this, 'postFormSucceeded'];
  return $form;
}
 public function postFormSucceeded(Form $form): void
{
    $data = $form->getValues();
    $this->database->table('comments')->insert([
        'post_id' => (int) $this->getParameter('id'),
        'name' => $data->name,
        'email' => $data->email,
        'content' => $data->content,
    ]);
    $this->redirect('this');
}
}