<?php
namespace app\includes\controller;

use app\includes\view\AddView;
use app\includes\model\AddModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

class AddController {
    private $view;
    private $addModel;
    private $errorModel;

    public function __construct() {
        if(!isset($_SESSION['user']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit;
        }

        $this->view = new AddView;
        $this->addModel = new AddModel;
        $this->errorModel = new ErrorModel;

        if(isset($_POST['addCityPressed']) && $_POST['addCityPressed'] == "Add City") {
            if(isset($_POST['city']) && !empty(trim($_POST['city'], " "))) {
                if(isset($_POST['xmlURL']) && !empty(trim($_POST['xmlURL'], " "))) {
                    if(isset($_POST['elements']) && !empty(trim($_POST['elements'], " "))) {
                        $this->validate();
                        $this->process();
                    }
                }
            } else {
                $this->errorModel->addErrorMessage('Fields cannot be empty!');
            }
        }
    }

    public function __destruct() {}

    public function validate() {
        $validator = new Validate();

        $validatedCityName = $validator->validateCity($_POST['city']);
        $validatedXMLURL = $validator->validateXMLURL($_POST['xmlURL']);
        $validatedElements = $validator->validateElements($_POST['elements']);

        if($validatedCityName !== false && $validatedXMLURL !== false && $validatedElements !== false) {
            $this->addModel->setCity($validatedCityName);
            $this->addModel->setXMLURL($validatedXMLURL);
            $this->addModel->setElements($validatedElements);
        } else {
            $this->errorModel->addErrorMessage('Your inputs failed to validate!');
        }
    }

    public function process() {
        $this->addModel->constructExecutionString();

        exec($this->addModel->getExecutionString());
    }

    public function getHtmlOutput() {
        if($this->errorModel->hasErrors()) {
            $_SESSION['error'] = serialize($this->errorModel);

            header('Location: error');
            exit();
        }

        $this->view->createSearchViewPage();
        return $this->view->getHtmlOutput();
    }
}
