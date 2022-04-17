<?php
namespace app\includes\controller;

use app\includes\view\RemoveView;
use app\includes\model\RemoveModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

/**
 * Controller for the Remove City section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can remove a City from the
 * database as well as end the XML Scraper attached to the city (if any).
 *
 * @since 0.0.1
 */
class RemoveController
{
    /* Properties */
    /**
     * Variable used to store the view of the Remove City component.
     *
     * @since 0.0.1
     *
     * @var RemoveView $view Instance of the RemoveView class.
     */
    private $view;

    /**
     * Variable used to store the remove model of the Remove City component.
     *
     * @since 0.0.1
     *
     * @var RemoveModel $removeModel Instance of the RemoveModel class.
     */
    private $removeModel;

    /**
     * Variable used to store the error model of the Remove City component.
     *
     * @since 0.0.1
     *
     * @var ErrorModel $view Instance of the ErrorModel class.
     */
    private $errorModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the RemoveController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instance, removing a city from the database).
     * The constructor also prevents any non-session (non-logged in) admins from entering
     * this specific section of the website.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     * @global array $_POST Global which stores the POST data.
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

        // Initialise the class' properties.
        $this->removeModel = new RemoveModel;
        $this->errorModel = new ErrorModel;

        // Only process the relevant inputs if the Remove City button is pressed.
        if(isset($_POST['city']) && (isset($_POST['removePressed']) && $_POST['removePressed'] == "Remove City")) {
            // Validate and Process the inputs.
            $this->validate();
            $this->process();
        }

        $this->view = new RemoveView;
    }

    /**
     * The destructor of the RemoveController class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to validate the user's input.
     *
     * This class method aims to validate the user's input by using defined class
     * methods of the class Validate. If any of the inputs fail to validate,
     * an error message is returned to the user, stating such fact. This method
     * aims to prevent a malicious user inputting harmful data which could
     * affect the web application / webserver.
     *
     * @since 0.0.1
     *
     * @see app\includes\core\Validate
     * @global array $_POST Global which stores the POST data.
     */
    public function validate()
    {
        // Store the returned values of the POST data being passed to various validation methods.
        $validatedCityName = Validate::validateCity($_POST['city']);

        // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
        if($validatedCityName !== false) {
            // Set the Remove Model's city name property so that it can be removed at a later date.
            $this->removeModel->setCityName($validatedCityName);
        } else {
            // Add an error message to the class' Error Model.
            $this->errorModel->addErrorMessage('The city entered does not exist!');
        }
    }

    /**
     * Class method which aims to process the user's validated input.
     *
     * This class method aims to process the user's validated input by using defined class
     * methods of the class RemoveModel. The method aims to remove the city from the database
     * as well as end the instance of the XML Scraper attached to the city (if available).
     *
     * @since 0.0.1
     *
     * @see app\includes\model\RemoveModel
     */
    public function process()
    {
        // Remove the city.
        $this->removeModel->removeCity();
    }

    /**
     * Class method which aims to return the HTML content of the Remove component.
     *
     * This class method aims to return the HTML content of the Remove component by
     * calling relevant classes on the RemoveView. If there are any errors present
     * throughout the Process and Validate methods, the user will be redirected
     * to the Error page, rather than the expected page.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\RemoveModel
     * @global array $_SESSION Global which stores session data.
     *
     * @return string String representation of the Remove components' HTML.
     */
    public function getHtmlOutput()
    {
        // Check to see if any errors have appeared throughout the Remove components' life.
        if($this->errorModel->hasErrors()) {
            // Store the Error Model in the global $_SESSION and serialize it.
            $_SESSION['error'] = serialize($this->errorModel);
            // TODO: add referrer as a field in error model.
            // Used to return the user back to the page in which the error occured.
            $_SESSION['referrer'] = 'remove';

            header('Location: error');
            exit();
        }

        // Create the HTML output.
        $this->view->createRemoveViewPage();
        return $this->view->getHtmlOutput();
    }
}
