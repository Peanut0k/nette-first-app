<?php

namespace App\Module\Front\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class LoginPresenter extends Nette\Application\UI\Presenter {
  public function createComponentLoginForm(string $name): Form {
    $form = new Form;
    $form->addText('username', 'Uživatelské jméno')
      ->setRequired('Zadejte své uživatelské jméno.');

    $form->addPassword('password', 'Heslo')
      ->setRequired('Zadejte své heslo.');

    $form->addSubmit('submit', 'Přihlásit se');

    $form->onSuccess[] = [$this, 'loginFormSucceeded'];
    return $form;
  }
  public function loginFormSucceeded(Form $form): void {
    $data = $form->getValues();
    try {
		$this->getUser()->login($data->username, $data->password);
    $this->flashMessage('Přihlášení bylo úspěšné.');
		$this->redirect('Homepage:default');

	} catch (Nette\Security\AuthenticationException $e) {
		$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
	}
  }
  public function actionOut(): void
{
	$this->getUser()->logout();
	$this->flashMessage('Odhlášení bylo úspěšné.');
	$this->redirect('Homepage:default');
}
}