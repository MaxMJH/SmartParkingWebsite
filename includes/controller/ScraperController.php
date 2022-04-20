<?php
namespace app\includes\controller;

use app\includes\view\ScraperView;
use app\includes\model\ScraperModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

/**
 * Controller for the Scraper section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can view the current XML Scrapers
 * and also end their processes if necessary.
 *
 * @since 0.0.1
 */
class ScraperController {
    /* Fields */
    /**
     * Variable used to store the view of the Scraper component.
     *
     * @since 0.0.1
     *
     * @var ScraperView $view Instance of the ScraperView class.
     */
    private $view;

    /**
     * Variable used to store the scraper model of the Scraper component.
     *
     * @since 0.0.1
     *
     * @var ScraperModel $scraperModel Instance of the ScraperModel class.
     */
    private $scraperModel;

    /**
     * Variable used to store the error model of the Scraper component.
     *
     * @since 0.0.1
     *
     * @var ErrorModel $view Instance of the ErrorModel class.
     */
    private $errorModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the ScraperController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instance, viewing current XML Scraper processes).
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
        $this->scraperModel = new ScraperModel;
        $this->errorModel = new ErrorModel;

        // Only process the relevant inputs if the End Process button is pressed.
        if(isset($_POST['endProcessPressed']) && !empty($_POST['endProcessPressed'])) {
            // Validate and Process the inputs.
            $this->validate();
            $this->process();
        }

        $this->view = new ScraperView;
    }

    /**
     * The destructor of the ScraperController class.
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
        $explodedData = explode("_", $_POST['endProcessPressed']);

        if(count($explodedData) == 2) {
            // Store the returned values of the POST data being passed to various validation methods.
            $validatedProcessID = Validate::validateProcessID($explodedData[1]);
            $validatedCityName = Validate::validateCity($explodedData[0]);

            // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
            if($validatedProcessID !== false && $validatedCityName !== false) {
                // Add the validated POST variables to the Scraper Model.
                $this->scraperModel->setCurrentProcessID($validatedProcessID);
                $this->scraperModel->setCurrentCityName($validatedCityName);
            } else {
                // Add an error message to the class' Error Model.
                $this->errorModel->addErrorMessage('Unable to validate!');
            }
        } else {
            // Add an error message to the class' Error Model.
            $this->errorModel->addErrorMessage('Unable to validate!');
        }
    }

    /**
     * Class method which aims to process the user's validated input.
     *
     * This class method aims to process the user's validated input by using defined class
     * methods of the class ScraperModel. The method aims to kill the process of the specified
     * process ID of the XML Scraper.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\ScraperModel
     */
    public function process()
    {
        // Check to see if the process has actually been killed.
        if(!$this->scraperModel->killProcess()) {
            // Add an error message to the class' Error Model.
            $this->errorModel->addErrorMessage('Unable to kill process!');
        }
    }

    /**
     * Class method which aims to return the HTML content of the Scraper component.
     *
     * This class method aims to return the HTML content of the Scraper component by
     * calling relevant classes on the ScraperView. If there are any errors present
     * throughout the Process and Validate methods, the user will be redirected
     * to the Error page, rather than the expected page.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\ScraperModel
     * @global array $_SESSION Global which stores session data.
     *
     * @return string String representation of the Scraper components' HTML.
     */
    public function getHtmlOutput()
    {
        // Check to see if any errors have appeared throughout the Scraper components' life.
        if($this->errorModel->hasErrors()) {
            // Store the Error Model in the global $_SESSION and serialize it.
            $_SESSION['error'] = serialize($this->errorModel);

            // Used to return the user back to the page in which the error occured.
            $_SESSION['referrer'] = 'scrapers';

            header('Location: error');
            exit();
        }

        // Create the HTML output.
        $this->view->createScraperViewPage();
        return $this->view->getHtmlOutput();
    }
}
