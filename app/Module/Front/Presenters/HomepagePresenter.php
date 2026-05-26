<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use App\Model\Facades\PostFacade;
use App\Model\Repositories\PostRepository;
use App\Model\Services\AuthorizationService;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter {
  public function __construct(
	private PostFacade $postFacade,
	private PostRepository $postRepository,
	private AuthorizationService $authorizationService,
  ) {

  }

 public function renderDefault(): void
	{
		$this->template->posts = $this->postRepository->getAllPosts();
		$this->template->user = $this->getUser();
		$this->template->authService = $this->authorizationService;
	}
}
