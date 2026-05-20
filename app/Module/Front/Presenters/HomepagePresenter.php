<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use App\Model\PostFacade;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter {
  public function __construct(private PostFacade $postFacade) {

  }

 public function renderDefault(): void
	{
		$this->template->posts = $this->postFacade
			->getPublicArticles()
			->limit(5);
	}
}
