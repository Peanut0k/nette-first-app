<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('login', 'Front:Login:login');
		$router->addRoute('post/create', 'Front:Post:Edit:create');
		$router->addRoute('<presenter>/<action>', 'Front:Homepage:default');
		return $router;
	}
}
