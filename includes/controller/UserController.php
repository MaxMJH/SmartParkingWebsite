<?php
namespace app\includes\controller;

use app\includes\view\UserView;
use app\includes\model\UserModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

class UserController {
    /* Properties */
    private $view;
    private $userModel;
    private $errorModel;

    /* Constructor and Destructor */
    public function __construct() {
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

        // Only process the relevant inputs if the End Process button is pressed.
        if(isset($_POST['deleteUserPressed']) && !empty($_POST['deleteUserPressed'])) {
            // Validate and Process the inputs.
            $this->validate();
            $this->process();
        }

        $this->view = new UserView;
    }

    public function __destruct() {

   }

   /* Methods */
   public function validate() {
       // Store the returned values of the POST data being passed to various validation methods.
       $validatedCityID = Validate::validateCityID($_POST['deleteUserPressed']);

       // If any of the POST data fails to validate, false is returned, therefore check if that is the case.
       if($validatedCityID !== false) {
           $this->userModel->setSelectedUserID($validatedCityID);
       } else {
           $this->errorModel->addErrorMessage('Unable to validate!');
       }
   }

   public function process() {
       if(!$this->userModel->deleteUser()) {
           $this->errorModel->addErrorMessage('Unable to remove user!');
       }
   }

   public function getHtmlOutput()
    {
        // Check to see if any errors have appeared throughout the Scraper components' life.
        if($this->errorModel->hasErrors()) {
            // Store the Error Model in the global $_SESSION and serialize it.
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
