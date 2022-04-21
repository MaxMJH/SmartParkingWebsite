<?php
namespace app\includes\core;

use app\includes\controller\LoginController;
use app\includes\controller\SearchController;
use app\includes\controller\ErrorController;
use app\includes\controller\ResultsController;
use app\includes\controller\AddController;
use app\includes\controller\RemoveController;
use app\includes\controller\SettingsController;
use app\includes\controller\ScraperController;
use app\includes\controller\UserController;
use app\includes\controller\EditUserController;

/**
 * Main class which handles all Routing methods necessary for the web application to function.
 *
 * Using a pre-defined array of routes, which ever request URI is entered, this class aims to
 * determine if the requested URI matches a route specified. If so, a controller will be created for
 * the route as well as a HTML output.
 *
 * @since 0.0.1
 */
class Router
{
    /* Properties */
    /**
     * Variable used to store the all possible routes required by the web application.
     *
     * @since 0.0.1
     * @var array $routes An array containing all routes needed.
     */
    private $routes;

    /* Constructor and Destructor */
    /**
     * The constructor of the Router class.
     *
     * The constructor intialises the required properties to ensure that the
     * class functions in the correct and specified manner (in this instance, creating an array to store future routes).
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->routes = array();
    }

    /**
     * The destructor of the Router class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Used to create an instance of the router class.
     *
     * The parameter $routesFile should be a script which contains all needed routes
     * for the web application. The require call essentially implements that script into this
     * class so that the routes can easily be loaded into the property $routes. After the fact,
     * the Router instance is returned so further method calls can be made after the routes have been loaded.
     *
     * @since 0.0.1
     *
     * @see app\includes\core\Routes
     *
     * @return Router An instance of the Router class.
     */
    public static function loadRoutes($routesFile)
    {
        // Create a static instance of the router.
        $router = new static;
        require $routesFile;
        return $router;
    }

    /**
     * Used to initialise a specific controller if the URI is within the $routes array.
     *
     * If the requested URI is a key within the $routes array, it's value will be returned
     * which contains a location to the respected controller. For example, if the URI entered
     * was 'search', the returned value should be 'controller/SearchController.php'. If the key
     * does exist, it's respective element is then thrown into a switch. After the fact, the
     * controller is then told to fetch the HTML output, effectively showing the page.
     *
     * @since 0.0.1
     *
     * @see app\includes\core\Routes
     *
     * @return string The HTML output of the respective controller.
     */
    public function redirectToRoute($uri)
    {
        // Check to see if the inputted URI is a key within the $routes array.
        if(array_key_exists($uri, $this->routes)) {
            // Get the controller location on the system, throw it into a switch statement and initialise it.
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
                case 'controller/RemoveController.php':
                    require 'controller/RemoveController.php';
                    $controller = new RemoveController;
                    break;
                case 'controller/ResultsController.php':
                    require 'controller/ResultsController.php';
                    $controller = new ResultsController;
                    break;
                case 'controller/ErrorController.php':
                    require 'controller/ErrorController.php';
                    $controller = new ErrorController;
                    break;
                case 'controller/SettingsController.php':
                    require 'controller/SettingsController.php';
                    $controller = new SettingsController;
                    break;
                case 'controller/ScraperController.php':
                    require 'controller/ScraperController.php';
                    $controller = new ScraperController;
                    break;
                case 'controller/UserController.php':
                    require 'controller/UserController.php';
                    $controller = new UserController;
                    break;
                case 'controller/EditUserController.php':
                    require 'controller/EditUserController.php';
                    $controller = new EditUserController;
                    break;
            }
            // Return the respective controller's HTML output.
            return $controller->getHtmlOutput();
        } else {
            // If the URI is not a key within the $routes array, re-direct the user back to the login page.
            require 'controller/LoginController.php';
            $controller = new LoginController;
            return $controller->getHtmlOutput();
        }
    }

    /**
     * Used to create a key value pair within the $routes array.
     *
     * Adds a key value pair to the $rotues array. It is expected that the routes script
     * will define their own routes. One potential element of the $routes array would be
     * the following: 'search' => 'controller/SearchController.php'.
     *
     * @since 0.0.1
     *
     * @see app\includes\core\Routes
     *
     * @param string $uri The Request URI (i.e. 'search').
     * @param string $controller The Controller location (i.e. 'controller/SearchController.php').
     */
    public function defineRoute($uri, $controller)
    {
        $this->routes[$uri] = $controller;
    }

    /* Getters and Setters */
    /**
     * Returns the $routes array.
     *
     * This method returns the array containing all routes needed for the website to function. It is
     * important to note that this method is used mainly for testing purposes to ensure that the
     * array has the correct values.
     *
     * @since 0.0.1
     *
     * @return array An array containing all routes specified.
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
