<?php
namespace app\includes\controller;

use app\includes\view\LoginView;
use app\includes\core\Validate;
use app\includes\model\LoginModel;
use app\includes\core\Encryption;

class LoginController {
    private $validatedInputs;
    private $isError;
    private $errorMessage;
    private $view;
    private $model;

    public function __construct() {
        $this->validatedInputs = array();
        $this->isError = false;
        $this->errorMessage = '';
        $this->view = new LoginView;
        $this->model = new LoginModel;

        if(isset($_POST['submit']) && $_POST['submit'] == 'Login') {
            if(isset($_POST['username']) && isset($_POST['password'])) {
                if(!empty($_POST['username']) && !empty($_POST['password'])) {
                    $this->validate();
                    $this->process();
                } else {
                    $this->isError = true;
                    $this->errorMessage = 'Fields cannot be empty!';
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
            $this->validatedInputs['emailAddress'] = $validatedEmailAddress;
            $this->validatedInputs['password'] = $validatedPassword;
        }
    }

    public function process() {
        $this->model->populateModel($this->validatedInputs);

        $saltAndPepper = $this->model->getSaltAndPepper();

        if($saltAndPepper['queryOK'] === true) {
            $salt = $saltAndPepper['result'][0]['salt'];
            $pepper = $saltAndPepper['result'][0]['pepper'];
            $password = Encryption::hashPassword($salt, $this->validatedInputs['password'], $pepper);

            $this->validatedInputs['password'] = $password;
            $this->model->populateModel($this->validatedInputs);
        }

        $user = $this->model->getUser();

	if($user['queryOK'] === true) {
            if($user['result'][0]['isAdmin'] === '1') {
                $_SESSION['userID'] = $user['result'][0]['userID'];
                $_SESSION['emailAddress'] = $user['result'][0]['emailAddress'];
                $_SESSION['firstName'] = $user['result'][0]['firstName'];
                $_SESSION['profilePicture'] = $user['result'][0]['profilePicture'];

                header('Location: search');
                exit;
            } else {
                $this->isError = true;
                $this->errorMessage = 'This user is not an admin!';
            }
        } else {
            $this->isError = true;
            $this->errorMessage = $user['result'];
        }
    }

    public function getHtmlOutput() {
        if($this->isError) {
            $this->view->setErrorMessage($this->errorMessage);
        }

        $this->view->createLoginViewPage();
        return $this->view->getHtmlOutput();
    }
}
