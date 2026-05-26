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
		$router->addRoute('admin/<action>', 'Front:Admin:default');
		$router->addRoute('register', 'Front:Register:in');
		$router->addRoute('login', 'Front:Login:in');
		$router->addRoute('post/create', 'Front:Post:Edit:create');
		$router->addRoute('<presenter>/<action>', 'Front:Homepage:default');
		return $router;
	}
}
