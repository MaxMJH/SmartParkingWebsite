<?php
namespace app\includes\controller;

use app\includes\view\LoginView;
use app\includes\core\Validate;
use app\includes\model\LoginModel;
use app\includes\model\ErrorModel;
use app\includes\core\Encryption;

class LoginController {
    private $view;
    private $loginModel;
    private $errorModel;

    public function __construct() {
        $this->view = new LoginView;
        $this->loginModel = new LoginModel;
        $this->errorModel = new ErrorModel;

        if(isset($_POST['submit']) && $_POST['submit'] == 'Login') {
            if(isset($_POST['username']) && isset($_POST['password'])) {
                if(!empty($_POST['username']) && !empty($_POST['password'])) {
                    if(isset($_SESSION['user'])) {
                        session_unset();
                        session_destroy();
                        $_SESSION = array();
                    }

                    $this->validate();
                    $this->process();
                } else {
                    $this->errorModel->addErrorMessage('Fields cannot be empty!');
                }
            }
        }
    }

    public function __destruct() {}

    public function validate() {
        $validator = new Validate;

        $validatedEmailAddress = $validator->validateUsername($_POST['username']);
        $validatedPassword = $validator->validatePassword($_POST['password']);

        if($validatedEmailAddress !== false && $validatedPassword !== false) {
            $this->loginModel->setEmailAddress($validatedEmailAddress);
            $this->loginModel->setPassword($validatedPassword);
        }
    }

    public function process() {
        if($this->loginModel->saltAndPepperPassword($this->loginModel->getPassword()) && $this->loginModel->populateUser()) {
            $_SESSION['user'] = serialize($this->loginModel);

            header('Location: search');
            exit();
	}
    }

    public function getHtmlOutput() {
        if($this->errorModel->hasErrors()) {
            $_SESSION['error'] = serialize($this->errorModel);

            header('Location: error');
            exit();
        }

        $this->view->createLoginViewPage();
        return $this->view->getHtmlOutput();
    }
}
