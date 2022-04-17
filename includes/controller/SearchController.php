<?php
namespace app\includes\controller;

use app\includes\view\SearchView;
use app\includes\model\SearchModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;
use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Controller for the Search section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can view a list of cities stored
 * within the database, of which they can select and view more in-depth parking data.
 *
 * @since 0.0.1
 */
class SearchController
{
    /* Properties */
    /**
     * Variable used to store the view of the Search component.
     *
     * @since 0.0.1
     * @var SearchView $view Instance of the SearchView class.
     */
    private $view;

    /**
     * Variable used to store the search model of the Search component.
     *
     * @since 0.0.1
     *
     * @var SearchModel $searchModel Instance of the SearchModel class.
     */
    private $searchModel;

    /**
     * Variable used to store the error model of the Add City component.
     *
     * @since 0.0.1
     * @var ErrorModel $view Instance of the ErrorModel class.
     */
    private $errorModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the SearchController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instance, view all cities in the database).
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
            exit;
        }

        // Initialise the class' properties.
        $this->searchModel = new SearchModel;
        $this->errorModel = new ErrorModel;
        $this->view = new SearchView;

        // Only process the relevant inputs if a city has been selected (pressed).
        if(isset($_POST['city'])) {
            // Validate and Process the inputs.
            $this->validate();
            $this->process();
        }
    }

    /**
     * The destructor of the SearchController class.
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
            // Add the validated POST variables to the Search Model.
            $this->searchModel->setCityName($validatedCityName);
        } else {
            // Add an error message to the class' Error Model.
            $this->errorModel->addErrorMessage('The city entered does not exist!');
        }
    }

    /**
     * Class method which aims to process the user's validated input.
     *
     * This class method aims to process the user's validated input by using defined class
     * methods of the class AddModel. The method aims to add the city to the database
     * (if it does not already exist) as well as start an instance of the XML Scraper.
     * If a process of the XML Scraper is already running for a specific City, the admin
     * will be notified of such issue. Once the XML Scraper has been started, a record of its
     * process ID and city name is stored in the database.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\AddModel
     */
    public function process()
    {
        // Get the city ID of the selected city.
        $this->searchModel->setCityID($this->searchModel->getCityName());

        // Once the city ID has been set, serialise the SearchModel so that it can be used by the Results page.
        $_SESSION['city'] = serialize($this->searchModel);

        header('Location: results');
        exit();
    }

    /**
     * Class method which aims to return the HTML content of the Search component.
     *
     * This class method aims to return the HTML content of the Search component by
     * calling relevant classes on the SearchView. If there are any errors present
     * throughout the Process and Validate methods, the user will be redirected
     * to the Error page, rather than the expected page.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\SearchModel
     * @global array $_SESSION Global which stores session data.
     *
     * @return string String representation of the Search components' HTML.
     */
    public function getHtmlOutput()
    {
        // Check to see if any errors have appeared throughout the Scraper components' life.
        if($this->errorModel->hasErrors()) {
            // Store the Error Model in the global $_SESSION and serialize it.
            $_SESSION['error'] = serialize($this->errorModel);

            // Used to return the user back to the page in which the error occured.
            $_SESSION['referrer'] = 'search';

            header('Location: error');
            exit();
        }

        // Create the HTML output.
        $this->view->createSearchViewPage();
        return $this->view->getHtmlOutput();
    }
}
