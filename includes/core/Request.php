<?php
namespace app\includes\core;

/**
 * Main class which grabs the requested webpage from the URI.
 *
 * After the host is entered (192.168.0.69) any forward slash followed by a page directory
 * will be trimmed by the class' static methods. This class is used for Routing.
 *
 * @since 0.0.1
 */
class Request
{
    /* Constructor and Destructor */
    /**
     * The constructor of the Request class.
     *
     * As the Request methods are static, the constructor is left blank.
     *
     * @since 0.0.1
     */
    public function __construct() {}

    /**
     * The destructor of the Request class.
     *
     * As the Request methods are static, the destructor is left blank.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Aims to return the directory in which the user wants to locate to.
     *
     * In this system, there are pre-defined routes, such as 'search', 'scrapers'.
     * This method aims to take any request after a '/' and parse it to the Router class.
     * If the following URL is entered (192.168.0.69/search), the $_SERVER['REQUEST_URI'] constant would return
     * '/search'. The trim function would remove the '/' leaving 'search'. Once this is returned, the route can
     * then be loaded.
     *
     * @since 0.0.1
     *
     * @global array $_SERVER Global which stores server data.
     *
     * @return string String containing the request directory to be used for routing.
     */
    public static function uri()
    {
        return trim($_SERVER['REQUEST_URI'], '/');
    }
}
