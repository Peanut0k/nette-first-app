<?php
namespace App\Module\Front\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class LoginPresenter extends Nette\Application\UI\Presenter {
  public function __construct(private Nette\Database\Explorer $database) {

  }

  public function renderLogin(): void {
    
  }

  public function createComponentLoginForm(): Form
  {
    $form = new Form;
    $form->addText('username', 'Uživatelské jméno')
      ->setRequired('Zadejte své uživatelské jméno.');

    $form->addPassword('password', 'Heslo')
      ->setRequired('Zadejte své heslo.');

    $form->addSubmit('submit', 'Přihlásit se');

    $form->onSuccess[] = [$this, 'loginFormSucceeded'];
    return $form;
  }

  public function loginFormSucceeded(Form $form): void
  {
    $data = $form->getValues();

    $this->redirect('this');
  }

}