<?php
namespace App\Model;

use Nette;

final class PostFacade
{
  public function __construct(
		private Nette\Database\Explorer $database,
	) {
	}
}