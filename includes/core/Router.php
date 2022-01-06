<?php
session_start();

class Router {
	private $routes;

	public function __construct() {}

	public function __destruct() {}

	public static function loadRoutes($routesFile) {
		$router = new static;
		require $routesFile;
		return $router;
	}

	public function redirectToRoute($uri) {
		if(array_key_exists($uri, $this->routes)) {
			$route = $this->routes[$uri];
			switch($route) {
				case 'controller/LoginController.php':
                                	require 'controller/LoginController.php';
                                        $controller = new LoginController;
					break;
				case 'controller/SearchController.php':
					require 'controller/SearchController.php';
					$controller = new SearchController;
					break;
				case 'controller/AddController.php':
					require 'controller/AddController.php';
					$controller = new AddController;
					break;
				case 'controller/ResultsController.php':
					require 'controller/ResultsController.php';
					$controller = new ResultsController;
					break;
				case 'controller/ErrorController.php':
					require 'controller/ErrorController.php';
					$controller = new ErrorController;
					break;
			}
			return $controller->getHtmlOutput();
		} else {
			require 'controller/LoginController.php';
                        $controller = new LoginController;
                        return $controller->getHtmlOutput();
		}
	}

	public function defineRoute($uri, $controller) {
		$this->routes[$uri] = $controller;
	}

	public function get($uri, $controller) {
		$this->routes['GET'][$uri] = $controller;
	}

	public function post($uri, $controller) {
		$this->routes['POST'][$uri] = $controller;
	}
}
