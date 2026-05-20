<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter {
  public function __construct(private Nette\Database\Explorer $database) {

  }

  public function renderDefault(): void {
    $this->template->posts = $this->database
    ->table('posts')
    ->order('created_at DESC')
    ->limit(5);
  }
}
