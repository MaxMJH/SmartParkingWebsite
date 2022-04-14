<?php
namespace app\includes\controller;

use app\includes\view\SettingsView;
use app\includes\model\SettingsModel;
use app\includes\model\LoginModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;
use app\includes\core\Encryption;

class SettingsController {
    private $view;
    private $settingsModel;
    private $user;
    private $errorModel;

    public function __construct() {
        if(!isset($_SESSION['user']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit();
        }

        $this->user = unserialize($_SESSION['user']);
        $this->settingsModel = new SettingsModel;
        $this->errorModel = new ErrorModel;

        if(isset($_POST['updateButton']) && $_POST['updateButton'] == 'Update') {
            $this->validate();
            $this->process();
        }

        $this->view = new SettingsView;
    }

    public function __destruct() {}

    public function validate() {
        $validator = new Validate;

        $validatedFirstName = $validator->validateFirstName($_POST['firstName']);
        $validatedLastName = $validator->validateLastName($_POST['lastName']);
        $validatedEmailAddress = $validator->validateUsername($_POST['emailAddress']);

        if(!empty($_POST['newPassword']) || !empty($_POST['confirmNewPassword'])) {
            $validatedNewPassword = $validator->validatePassword($_POST['newPassword']);
            $validatedConfirmNewPassword = $validator->validatePassword($_POST['confirmNewPassword']);

            if($validatedFirstName !== false && $validatedLastName !== false && $validatedEmailAddress !== false && $validatedNewPassword !== false && $validatedConfirmNewPassword !== false) {
                if($validatedNewPassword == $validatedConfirmNewPassword) {
                    $this->user->setPassword($validatedNewPassword);
                    $this->user->saltAndPepperPassword();
                } else {
                    $this->errorModel->addErrorMessage('The passwords do not match!');
                }

                $this->user->setFirstName($validatedFirstName);
                $this->user->setLastName($validatedLastName);
                $this->user->setEmailAddress($validatedEmailAddress);
            }
        } else {
            if($validatedFirstName !== false && $validatedLastName !== false && $validatedEmailAddress !== false) {
                $this->user->setFirstName($validatedFirstName);
                $this->user->setLastName($validatedLastName);
                $this->user->setEmailAddress($validatedEmailAddress);
            }
        }
    }

    public function process() {
        if($this->settingsModel->updateUser($this->user) === true) {
            $_SESSION['user'] = serialize($this->user);
        } else {
            $this->errorModel->addErrorMessage('Unable to update user details!');
        }
    }

    public function getHtmlOutput() {
        if($this->errorModel->hasErrors()) {
            $_SESSION['error'] = serialize($this->errorModel);
            $_SESSION['referrer'] = 'settings';

            header('Location: error');
            exit();
        }

        $this->view->createSettingsViewPage();
        return $this->view->getHtmlOutput();
    }
}
