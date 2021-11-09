<?php
class Router {
	protected $routes = [
		'GET' => [],
		'POST' => []
	];

	public static function load($file) {
		$router = new static;
		require $file;
		return $router;
	}

	public function direct($uri, $requestType) {
		if(array_key_exists($uri, $this->routes[$requestType])) {
			$route = $this->routes[$requestType][$uri];
			switch($route) {
				case 'controller/LoginController.php':
                                	require 'controller/LoginController.php';
                                        $controller = new LoginController;
					break;
				case 'controller/SearchController.php':
					require 'controller/SearchController.php';
					$controller = new SearchController;
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

	public function get($uri, $controller) {
		$this->routes['GET'][$uri] = $controller;
	}

	public function post($uri, $controller) {
		$this->routes['POST'][$uri] = $controller;
	}
}
