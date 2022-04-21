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
     * @var ErrorModel $view Instance of the ErrorModel class.
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

        if(isset($_POST['isAdmin']) && $_POST['isAdmin'] === "isAdmin") {
            $this->editUserModel->getLoginModel()->setIsAdmin(true);
        } else {
            $this->editUserModel->getLoginModel()->setIsAdmin(false);
        }
    }

    public function process()
    {
        if($this->editUserModel->updateUser()) {
            $_SESSION['edit-user'] = serialize($this->editUserModel);

            header('Location: users');
            exit();
        } else {
            $this->errorModel->addErrorMessage('Failed to update user!');
        }
    }

    /**
     * Class method which aims to process the user's validated input.
     *
     * This class method aims to process the user's validated input by using defined class
     * methods of the class SettingsModel. The method aims to update the newly eneted information
     * if it is valid. Once updated, the serialised login model is re-serialised so that the data
     * is updated, allowing the admin to carry on rather than having to restart the session.
     *
     * @since 0.0.1
     *
     * @see app\includes\model\SettingsModel
     * @see app\includes\model\LoginModel
     */
    public function setUser()
    {
        $users = unserialize($_SESSION['users'])->getUsers();

        for($i = 0; $i < count($users); $i++) {
            if($users[$i]->getUserID() == $_POST['editUserPressed']) {
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
     * @see app\includes\model\SettingsModel
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
