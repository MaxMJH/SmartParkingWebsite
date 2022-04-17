<?php
namespace app\includes\controller;

use app\includes\model\ErrorModel;
use app\includes\view\ErrorView;

/**
 * Controller for the Error section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, if the admin enters or uses the web application
 * incorrectly, an error will be thrown to notify of such problem.
 *
 * @since 0.0.1
 */
class ErrorController
{
    /* Properties */
    /**
     * Variable used to store the view of the Error component.
     *
     * @since 0.0.1
     *
     * @var ErrorView $view Instance of the ErrorView class.
     */
    private $view;

    /* Constructor and Destructor */
    /**
     * The constructor of the ErrorController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instace, showing errors).
     * The constructor also prevents any non-session (non-logged in) admins from entering
     * this specific section of the website.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     */
    public function __construct()
    {
        // Check to see if the user is not logged in or if the user has pressed the logout button.
        if(!isset($_SESSION['user']) || isset($_POST['logout'])) {
            // Destroy the session and redirect the user back to the login page.
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit();
        }

        $this->view = new ErrorView;
    }

    /**
     * The destructor of the ErrorController class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to return the HTML content of the Error component.
     *
     * This class method aims to return the HTML content of the Error component by
     * calling relevant classes on the ErrorView. Any errors which have occured will
     * be presented in this view.
     *
     * @since 0.0.
     *
     * @return string String representation of the Error components' HTML.
     */
    public function getHtmlOutput()
    {
        $this->view->createErrorViewPage();
        return $this->view->getHtmlOutput();
    }
}
