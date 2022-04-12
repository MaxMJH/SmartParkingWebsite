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
        $saltAndPepper = $this->loginModel->getSaltAndPepper();

        if($saltAndPepper['queryOK'] === true) {
            $salt = $saltAndPepper['result'][0]['salt'];
            $pepper = $saltAndPepper['result'][0]['pepper'];
            $password = Encryption::hashPassword($salt, $this->loginModel->getPassword(), $pepper);

            $this->loginModel->setPassword($password);
        }

        $user = $this->loginModel->getUser();

	if($user['queryOK'] === true) {
            if($user['result'][0]['isAdmin'] === '1') {
                $this->loginModel->setUserID($user['result'][0]['userID']);
                $this->loginModel->setEmailAddress($user['result'][0]['emailAddress']);
                $this->loginModel->setFirstName($user['result'][0]['firstName']);
                $this->loginModel->setLastName($user['result'][0]['lastName']);
                $this->loginModel->setProfilePicture($user['result'][0]['profilePicture']);

                $_SESSION['user'] = serialize($this->loginModel);

                header('Location: search');
                exit;
            } else {
                $this->errorModel->addErrorMessage('This user is not an admin!');
            }
        } else {
            $this->errorModel->addErrorMessage($user['result']);
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
