<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use App\Model\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;

final class RegisterPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private UserRepository $userRepository,
        private Passwords $passwords,
    ) {
    }

    public function createComponentRegisterForm(string $name): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno')
            ->setRequired('Zadejte své uživatelské jméno.');

        $form->addPassword('password', 'Heslo')
            ->setRequired('Zadejte své heslo.');

        $form->addPassword('password_confirm', 'Potvrzení hesla')
            ->setRequired('Potvrďte vaše heslo.')
            ->addRule(Form::EQUAL, 'Hesla se musí shodovat.', $form['password']);

        $form->addSubmit('submit', 'Registrovat se');
        $form->onSuccess[] = [$this, 'registerFormSucceeded'];

        return $form;
    }

    public function registerFormSucceeded(Form $form): void
    {
        $data = $form->getValues('array');

        if ($this->userRepository->findByUsername($data['username'])) {
            $form->addError('Uživatelské jméno již existuje. Zvolte prosím jiné.');
            return;
        }

        $this->userRepository->create([
            'username' => $data['username'],
            'password' => $this->passwords->hash($data['password']),
        ]);

        $this->flashMessage('Registrace proběhla úspěšně. Nyní se můžete přihlásit.', 'success');
        $this->redirect('Login:in');
    }
}
