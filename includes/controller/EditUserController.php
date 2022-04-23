<?php
namespace app\includes\controller;

use app\includes\view\EditUserView;
use app\includes\model\EditUserModel;
use app\includes\model\UserModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;
use app\includes\core\Encryption;

/**
 * Controller for the EditUser section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. Using this class, the admin can edit their an existing user's information
 * such as their name, email, password, etc...
 *
 * @since 0.0.1
 */
class EditUserController
{
    /* Properties */
    /**
     * Variable used to store the view of the EditUser component.
     *
     * @since 0.0.1
     *
     * @var EditUserView $view Instance of the EditUserView class.
     */
    private $view;

    /**
     * Variable used to store the edit user model of the EditUser component.
     *
     * @since 0.0.1
     *
     * @var EditUserModel $editUserModel Instance of the EditUserModel class.
     */
    private $editUserModel;

    /**
     * Variable used to store the user model of the EditUser component.
     *
     * @since 0.0.1
     *
     * @var UserModel $userModel Instance of the UserModel class.
     */
    private $userModel;

    /**
     * Variable used to store the error model of the EditUser component.
     *
     * @since 0.0.1
     *
     * @var ErrorModel $errorModel Instance of the ErrorModel class.
     */
    private $errorModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the EditUserController class.
     *
     * The constructor intialises the required properties to ensure that the
     * controller functions in the correct and specified manner (in this instance, altering user data).
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
        $this->editUserModel = new EditUserModel;
        $this->userModel = new UserModel;
        $this->errorModel = new ErrorModel;

        // Only process the relevant inputs if the Update button is pressed.
        if(isset($_POST['editUserPressed']) && !empty($_POST['editUserPressed'])) {
            $this->setUser();
        }

        if(!isset($_SESSION['edit-user'])) {
            header('Location: users');
            exit();
        }

        if(isset($_POST['updateButton']) && $_POST['updateButton'] == 'Update') {
            $this->validate();
            $this->process();
        }

        $this->view = new EditUserView;
    }

    /**
     * The destructor of the EditUserController class.
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
        $this->editUserModel = unserialize($_SESSION['edit-user']);

        // Store the returned values of the POST data being passed to various validation methods.
        $validatedFirstName = Validate::validateName($_POST['firstName']);
        $validatedLastName = Validate::validateName($_POST['lastName']);
        $validatedEmailAddress = Validate::validateUsername($_POST['emailAddress']);

        // Check to see if the admin wants to change their password.
        if(!empty($_POST['newPassword']) || !empty($_POST['confirmNewPassword'])) {
            // Store the returned values of the POST data being passed to various validation methods.
            $validatedNewPassword = Validate::validatePassword($_POST['newPassword']);
            $validatedConfirmNewPassword = Validate::validatePassword($_POST['confirmNewPassword']);

            // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
            if($validatedFirstName !== false &&
               $validatedLastName !== false &&
               $validatedEmailAddress !== false &&
               $validatedNewPassword !== false &&
               $validatedConfirmNewPassword !== false) {
                // Check to see if the two password inputs are the same.
                if($validatedNewPassword == $validatedConfirmNewPassword) {
                    // Add the validated POST variables to the Login Model.
                    $this->editUserModel->getLoginModel()->setPassword($validatedNewPassword);

                    // Salt the plaintext password before updating the password.
                    $this->editUserModel->getLoginModel()->saltAndPepperPassword();
                } else {
                    // Add an error message to the class' Error Model.
                    $this->errorModel->addErrorMessage('The passwords do not match!');
                }

                // Add the validated POST variables to the Login Model.
                $this->editUserModel->getLoginModel()->setFirstName($validatedFirstName);
                $this->editUserModel->getLoginModel()->setLastName($validatedLastName);
                $this->editUserModel->getLoginModel()->setEmailAddress($validatedEmailAddress);
            }
        } else {
            // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
            if($validatedFirstName !== false && $validatedLastName !== false && $validatedEmailAddress !== false) {
                // Add the validated POST variables to the Login Model.
                $this->editUserModel->getLoginModel()->setFirstName($validatedFirstName);
                $this->editUserModel->getLoginModel()->setLastName($validatedLastName);
                $this->editUserModel->getLoginModel()->setEmailAddress($validatedEmailAddress);
            } else {
                // Add an error message to the class' Error Model.
                $this->errorModel->addErrorMessage('The data entered failed to validate!');
            }
        }

        // Determine whether or not the user's privileges should be elevated or not.
        if(isset($_POST['isAdmin']) && $_POST['isAdmin'] === "isAdmin") {
            $this->editUserModel->getLoginModel()->setIsAdmin(true);
        } else {
            $this->editUserModel->getLoginModel()->setIsAdmin(false);
        }
    }

    /**
     * Class method which aims to process the user's validated input.
     *
     * This class method aims to process the user's validated input by using defined class
     * methods of the class EditUserModel. The method aims to take all validated data inputted by
     * an admin and attempt to update said values in the database. If the data fails to update
     * then such error will be conveyed to the admin.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\EditUserModel
     */
    public function process()
    {
        // Attempt to update the user's information.
        if($this->editUserModel->updateUser()) {
            $_SESSION['edit-user'] = serialize($this->editUserModel);

            // If updated successfully, redirect the user back to the 'Users' page.
            header('Location: users');
            exit();
        } else {
            // Notify the admin that the inputted values failed to update for a specific user.
            $this->errorModel->addErrorMessage('Failed to update user!');
        }
    }

    /**
     * Class method which aims to put a specific user in editing focus.
     *
     * This method aims to setup the user which the admin wishes to edit. Firstly,
     * from the 'Users' page, all registered users (besides the logged in admin) are
     * fetched from the $_SESSION global and each of their ID's are checked against the
     * $_POST data. If both are a match, the LoginModel is then set allowing for the
     * EditUserView to display the correct user details.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\LoginModel
     * @see app\includes\core\Validate
     * @see app\includes\model\EditUserModel
     * @global array $_SESSION Global which stores the SESSION data.
     * @global array $_POST Global which stores the POST data.
     */
    public function setUser()
    {
        // Get all registered users.
        $users = unserialize($_SESSION['users'])->getUsers();

        // Iterate through all users to check if the requested edited user actually exists.
        for($i = 0; $i < count($users); $i++) {
            if($users[$i]->getUserID() == Validate::validateID($_POST['editUserPressed'])) {
                // If the user does exist, set the LoginModel for the EditUserModel so that the user can be edited.
                $this->editUserModel->setLoginModel($users[$i]);
                $_SESSION['edit-user'] = serialize($this->editUserModel);
            }
        }
    }

    /**
     * Class method which aims to return the HTML content of the EditUser component.
     *
     * This class method aims to return the HTML content of the EditUser component by
     * calling relevant classes on the EditUserView. If there are any errors present
     * throughout the Process and Validate methods, the user will be redirected
     * to the Error page, rather than the expected page.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\ErrorModel
     * @see app\includes\view\EditUserView
     * @global array $_SESSION Global which stores session data.
     *
     * @return string String representation of the EditUser components' HTML.
     */
    public function getHtmlOutput()
    {
        // Check to see if any errors have appeared throughout the EditUser components' life.
        if($this->errorModel->hasErrors()) {
            // Store the Error Model in the global $_SESSION and serialize it.
            $_SESSION['error'] = serialize($this->errorModel);

            // Used to return the user back to the page in which the error occured.
            $_SESSION['referrer'] = 'users';

            header('Location: error');
            exit();
        }

        // Create the HTML output.
        $this->view->createEditUserViewPage();
        return $this->view->getHtmlOutput();
    }
}
