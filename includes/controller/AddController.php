<?php
namespace app\includes\controller;

use app\includes\view\AddView;
use app\includes\model\AddModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

/**
 * Controller for the Add City section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can add a new City to the
 * database as well as start the XML Scraper upon the XML of choice.
 *
 * @since 0.0.1
 */
class AddController
{
    /* Properties */
    /**
     * Variable used to store the view of the Add City component.
     *
     * @since 0.0.1
     *
     * @var AddView $view Instance of the AddView class.
     */
    private $view;

    /**
     * Variable used to store the add model of the Add City component.
     *
     * @since 0.0.1
     *
     * @var AddModel $addModel Instance of the AddModel class.
     */
    private $addModel;

    /**
     * Variable used to store the error model of the Add City component.
     *
     * @since 0.0.1
     *
     * @var ErrorModel $view Instance of the ErrorModel class.
     */
    private $errorModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the AddController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instance, adding a city to the database).
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
        $this->view = new AddView;
        $this->addModel = new AddModel;
        $this->errorModel = new ErrorModel;

        // Only process the relevant inputs if the Add City button is pressed. Also ensure that none of the inputs are empty.
        if(isset($_POST['addCityPressed']) && $_POST['addCityPressed'] == "Add City") {
            if(isset($_POST['city']) && !empty(trim($_POST['city'], " ")) &&
               isset($_POST['xmlURL']) && !empty(trim($_POST['xmlURL'], " ")) &&
               isset($_POST['elements']) && !empty(trim($_POST['elements'], " "))) {
                // Validate and Process the inputs.
                $this->validate();
                $this->process();
            } else {
                // Add an error message to the class' Error Model.
                $this->errorModel->addErrorMessage('Fields cannot be empty!');
            }
        }
    }

    /**
     * The destructor of the AddController class.
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
        $validatedXMLURL = Validate::validateXMLURL($_POST['xmlURL']);
        $validatedElements = Validate::validateElements($_POST['elements']);

        // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
        if($validatedCityName !== false && $validatedXMLURL !== false && $validatedElements !== false) {
            // Add the validated POST variables to the Add Model.
            $this->addModel->setCity($validatedCityName);
            $this->addModel->setXMLURL($validatedXMLURL);
            $this->addModel->setElements($validatedElements);
        } else {
            // Add an error message to the class' Error Model.
            $this->errorModel->addErrorMessage('Your inputs failed to validate!');
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
        // Check to see if a XML Scraper is already attached to the requested City.
        if($this->addModel->scraperIsActive() === false) {
            // Construct and execute the string which starts the XML Scraper.
            $this->addModel->constructExecutionString();
            exec($this->addModel->getExecutionString());

            // As the XMLScraper has to parse an entire document, adding the City and Carparks to the db will take a "long" time, so add a delay.
            sleep(2);

            // Add the XML Scraper's process ID to the database.
            $this->addModel->generateScraperRecord();
        } else {
            // Add an error message to the class' Error Model.
            $this->errorModel->addErrorMessage('This city already exists! If you need to add new carparks, first end the scraper attached to the city, then try and add it again!');
        }
    }

    /**
     * Class method which aims to return the HTML content of the Add component.
     *
     * This class method aims to return the HTML content of the Add component by
     * calling relevant classes on the AddView. If there are any errors present
     * throughout the Process and Validate methods, the user will be redirected
     * to the Error page, rather than the expected page.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\AddModel
     * @global array $_SESSION Global which stores session data.
     *
     * @return string String representation of the Add components' HTML.
     */
    public function getHtmlOutput()
    {
        // Check to see if any errors have appeared throughout the Add components' life.
        if($this->errorModel->hasErrors()) {
            // Store the Error Model in the global $_SESSION and serialize it.
            $_SESSION['error'] = serialize($this->errorModel);

            // Used to return the user back to the page in which the error occured.
            $_SESSION['referrer'] = 'add';

            header('Location: error');
            exit();
        }

        // Create the HTML output.
        $this->view->createAddViewPage();
        return $this->view->getHtmlOutput();
    }
}
