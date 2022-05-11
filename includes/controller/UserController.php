<?php
namespace app\includes\controller;

use app\includes\view\UserView;
use app\includes\model\UserModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

/**
 * Controller for the User section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can view and remove a user from the
 * database.
 *
 * @since 0.0.1
 */
class UserController
{
    /* Properties */
    /**
     * Variable used to store the view of the User component.
     *
     * @since 0.0.1
     *
     * @var UserView $view Instance of the UserView class.
     */
    private $view;

    /**
     * Variable used to store the user model of the User component.
     *
     * @since 0.0.1
     *
     * @var UserModel $userModel Instance of the UserModel class.
     */
    private $userModel;

    /**
     * Variable used to store the error model of the User component.
     *
     * @since 0.0.1
     *
     * @var ErrorModel $errorModel Instance of the ErrorModel class.
     */
    private $errorModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the UserController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instance, viewing and removing user data).
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
        $this->userModel = new UserModel;
        $this->errorModel = new ErrorModel;

        // Only process the relevant inputs if the Delete User button is pressed.
        if(isset($_POST['deleteUserPressed']) && !empty($_POST['deleteUserPressed'])) {
            // Validate and Process the inputs.
            $this->validate();
            $this->process();
        }

        $this->view = new UserView;
    }

    /**
     * The destructor of the UserController class.
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
        $validatedCityID = Validate::validateCityID($_POST['deleteUserPressed']);

        // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
        if($validatedCityID !== false) {
            $this->userModel->setSelectedUserID($validatedCityID);
        } else {
            $this->errorModel->addErrorMessage('Unable to validate!');
        }
    }

    /**
     * Class method which aims to process the user's validated input.
     *
     * This class method aims to process the user's validated input by using defined class
     * methods of the class UserController. The method aims to update remove a specified user
     * from the database if they exist.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\UserModel
     * @see app\includes\model\ErrorModel
     */
    public function process()
    {
        if(!$this->userModel->deleteUser()) {
            $this->errorModel->addErrorMessage('Unable to remove user!');
        }
    }

    /**
     * Class method which aims to return the HTML content of the User component.
     *
     * This class method aims to return the HTML content of the User component by
     * calling relevant classes on the UserView. If there are any errors present
     * throughout the Process and Validate methods, the user will be redirected
     * to the Error page, rather than the expected page.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\ErrorModel
     * @see app\includes\view\UserView
     * @global array $_SESSION Global which stores session data.
     *
     * @return string String representation of the Settings components' HTML.
     */
    public function getHtmlOutput()
    {
        // Check to see if any errors have appeared throughout the User components' life.
        if($this->errorModel->hasErrors()) {
            // Store the Error Model in the global $_SESSION and serialise it.
            $_SESSION['error'] = serialize($this->errorModel);

            // Used to return the user back to the page in which the error occured.
            $_SESSION['referrer'] = 'users';

            header('Location: error');
            exit();
        }

        // Create the HTML output.
        $this->view->createUserViewPage();
        return $this->view->getHtmlOutput();
    }
}
