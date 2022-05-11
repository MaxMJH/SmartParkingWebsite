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
        $router->defineRoute('remove', 'controller/RemoveController.php');
        $router->defineRoute('error', 'controller/ErrorController.php');
        $router->defineRoute('results', 'controller/ResultsController.php');
        $router->defineRoute('settings', 'controller/SettingsController.php');
        $router->defineRoute('scrapers', 'controller/ScraperController.php');
        $router->defineRoute('users', 'controller/UserController.php');
        $router->defineRoute('users-edit', 'controller/EditUserController.php');
        $router->defineRoute('reviews', 'controller/ReviewController.php');

        $this->assertSame(count($router->getRoutes()), 11);
    }
}
