<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\core\Router;

class TestRouter extends TestCase {
	public function testInstantiateRouter() {
		$router = new Router;

		$this->assertSame(count($router->getRoutes()), 0);
	}

	public function testRoutesSize() {
		$router = new Router;
		$router->defineRoute('/', 'controller/LoginController.php');
		$router->defineRoute('search', 'controller/SearchController.php');
		$router->defineRoute('add', 'controller/AddController.php');
		$router->defineRoute('error', 'controller/ErrorController.php');
		$router->defineRoute('results', 'controller/ResultsController.php');

		$this->assertSame(count($router->getRoutes()), 5);
	}
}
