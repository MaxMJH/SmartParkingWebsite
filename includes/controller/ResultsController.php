<?php
namespace app\includes\controller;

use app\includes\model\ResultsModel;
use app\includes\view\ResultsView;

/**
 * Controller for the Results section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can view the scraped results of
 * a city. These results contain all collected Carparking data.
 *
 * @since 0.0.1
 */
class ResultsController
{
    /* Properties */
    /**
     * Variable used to store the view of the Results component.
     *
     * @since 0.0.1
     *
     * @var ResultsView $view Instance of the ResultsView class.
     */
    private $view;

    /**
     * Variable used to store the results model of the Results component.
     *
     * @since 0.0.1
     *
     * @var ResultsModel $resultsModel Instance of the ResultsModel class.
     */
    private $resultsModel;

    /**
     * Variable used to store the search model of the Search component.
     *
     * @since 0.0.1
     *
     * @var SearchModel $searchModel Instance of the SearchModel class.
     */
    private $searchModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the ResultsController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instance, viewing the data stored on a specific city).
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

        // Check to see if a city has actually been selected. If not, redirect the admin back to the search page.
        if(!isset($_SESSION['city'])) {
            header('Location: search');
            exit();
        }

        // Initialise the class' properties.
        $this->resultsModel = new ResultsModel;
        $this->searchModel = unserialize($_SESSION['city']);
	      $this->processResultsTable();
        $this->view = new ResultsView;
    }

    /**
     * The destructor of the ResultsController class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to populate the relevant tables of the Resutls component.
     *
     * If a city has been selected via the search page, it's City ID is collected and stored
     * into the Results Model. Once stored in the Results Model, the relevant array which aim to store
     * data such as, FiveMinutes car parking data, is loaded. Once completed, the Results Model is serialised
     * and stored in the global array $_SESSION to be used throughout the web application.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\ResultsModel
     * @see app\includes\model\SearchModel
     * @global array $_SESSION Global which stores session data.
     */
    public function processResultsTable()
    {
        // Grab the City ID from the Search Model and store it in the Results Model.
        $this->resultsModel->setCityID($this->searchModel->getCityID());

        // Populate the relevant arrays to be used by the view.
	      $this->resultsModel->setFiveMinutes();
        $this->resultsModel->setHourly();
        $this->resultsModel->setDaily();
        $this->resultsModel->setCarparks();

        // Serialise the newly populate Results Model.
        $_SESSION['results'] = serialize($this->resultsModel);
    }

    /**
     * Class method which aims to return the HTML content of the Results component.
     *
     * This class method aims to return the HTML content of the Results component by
     * calling relevant classes on the ResultsView.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\ResultsModel
     *
     * @return string String representation of the Results components' HTML.
     */
    public function getHtmlOutput()
    {
        $this->view->createResultsViewPage();
        return $this->view->getHtmlOutput();
    }
}
