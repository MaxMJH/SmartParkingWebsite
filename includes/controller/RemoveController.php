<?php
namespace app\includes\controller;

use app\includes\view\RemoveView;
use app\includes\model\RemoveModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

class RemoveController {
    /* Fields */
    private $view;
    private $removeModel;
    private $errorModel;

    /* Constructor and Destructor */
    public function __construct() {
        if(!isset($_SESSION['user']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit();
        }

        $this->removeModel = new RemoveModel;
        $this->errorModel = new ErrorModel;

        if(isset($_POST['city']) && (isset($_POST['removePressed']) && $_POST['removePressed'] == "Remove City")) {
            $this->validate();
            $this->process();
        }

        $this->view = new RemoveView;
    }

    public function __destruct() {}

    /* Methods */
    public function validate() {
        $validator = new Validate;

        $validatedCityName = $validator->validateCity($_POST['city']);

        if($validatedCityName !== false) {
            $this->removeModel->setCityName($validatedCityName);
        } else {
            $this->errorModel->addErrorMessage('The city entered does not exist!');
        }
    }

    public function process() {
        $this->removeModel->removeCity();
    }

    public function getHtmlOutput() {
        if($this->errorModel->hasErrors()) {
            $_SESSION['error'] = serialize($this->errorModel);
            // TODO: add referrer as a field in error model.
            $_SESSION['referrer'] = 'remove';

            header('Location: error');
            exit();
        }

        $this->view->createRemoveViewPage();
        return $this->view->getHtmlOutput();
    }
}


