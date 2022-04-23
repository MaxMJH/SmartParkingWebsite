<?php
namespace app\includes\controller;

use app\includes\view\LoginView;
use app\includes\core\Validate;
use app\includes\model\LoginModel;
use app\includes\core\Encryption;

/**
 * Controller for the Login section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can login to their specified account,
 * if the credentials are correct.
 *
 * @since 0.0.1
 */
class LoginController
{
    /* Properties */
    /**
     * Variable used to store the view of the Login component.
     *
     * @since 0.0.1
     *
     * @var LoginView $view Instance of the LoginView class.
     */
    private $view;

    /**
     * Variable used to store the login model of the Login component.
     *
     * @since 0.0.1
     *
     * @var LoginModel $loginModel Instance of the LoginModel class.
     */
    private $loginModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the LoginController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instane, allowing the admin to login).
     * The constructor also prevents any non-session (non-logged in) admins from entering
     * this specific section of the website.
     *
     * @since 0.0.1
     *
     * @global array $_POST Global which stores the POST data.
     * @global array $_SESSION Global which stores session data.
     */
    public function __construct()
    {
        // Initialise the class' properties.
        $this->view = new LoginView;
        $this->loginModel = new LoginModel;

        // Only process the relevant inputs if the Login button is pressed. Also ensure that none of the inputs are empty.
        if(isset($_POST['submit']) && $_POST['submit'] == 'Login') {
            if(isset($_POST['username']) &&
               isset($_POST['password']) &&
               !empty($_POST['username']) &&
               !empty($_POST['password'])) {
                // If the user is already logged in, destroy the session.
                if(isset($_SESSION['user'])) {
                    session_unset();
                    session_destroy();
                    $_SESSION = array();
                }

                // Validate and Process the inputs.
                $this->validate();
                $this->process();
            }
        }
    }

    /**
     * The destructor of the LoginController class.
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
     * @see app\includes\model\LoginModel
     * @global array $_POST Global which stores the POST data.
     */
    public function validate()
    {
        // Store the returned values of the POST data being passed to various validation methods.
        $validatedEmailAddress = Validate::validateUsername($_POST['username']);
        $validatedPassword = Validate::validatePassword($_POST['password']);

        // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
        if($validatedEmailAddress !== false && $validatedPassword !== false) {
            // Add the validated POST variables to the Login Model.
            $this->loginModel->setEmailAddress($validatedEmailAddress);
            $this->loginModel->setPassword($validatedPassword);
        }
    }

    /**
     * Class method which aims to process the user's validated input.
     *
     * This class method aims to process the user's validated input by using defined class
     * methods of the class LoginModel. If the entered password matches that of the database,
     * the user has 'successfully' logged into the web application. The LoginModel is serialised
     * and stored in the global array $_SESSION so that it can be used throughout the site by other
     * components.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\loginModel
     * @global array $_SESSION Global which stores session data.
     */
    public function process()
    {
        // Check to see if both the email and password entered (once salt, peppered and hashed) match a record in the database.
        if($this->loginModel->saltAndPepperPassword($this->loginModel->getPassword()) && $this->loginModel->populateUser()) {
            if($this->loginModel->getIsAdmin()) {
                // Serialise the LoginModel so that other components can use it at a later date.
                $_SESSION['user'] = serialize($this->loginModel);

                // Redirect the user to the search page.
                header('Location: search');
                exit();
            }
        }
    }

    /**
     * Class method which aims to return the HTML content of the Login component.
     *
     * This class method aims to return the HTML content of the Login component by
     * calling relevant classes on the LoginView.
     *
     * @since 0.0.1
     *
     * @see app\includes\view\LoginView
     * @global array $_SESSION Global which stores session data.
     *
     * @return string String representation of the Login components' HTML.
     */
    public function getHtmlOutput()
    {
        // Create the HTML output.
        $this->view->createLoginViewPage();
        return $this->view->getHtmlOutput();
    }
}
