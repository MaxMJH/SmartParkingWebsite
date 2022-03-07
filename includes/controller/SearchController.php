<?php
namespace app\includes\controller;

use app\includes\view\SearchView;
use app\includes\model\SearchModel;
use app\includes\core\Validate;
use app\includes\core\Database;
use app\includes\core\Queries;

class SearchController {
    private $view;
    private $model;
    private $validatedInput;
    private $errorMessage;
    private $isError;

    public function __construct() {
        if(!isset($_SESSION['userID']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit;
        }

        $this->view = new SearchView;
        $this->model = new SearchModel;
        $this->errorMessage = '';
        $this->isError = false;

        if(isset($_POST['searchPressed']) && $_POST['searchPressed'] == "Search") {
            if(isset($_POST['city']) && !empty(trim($_POST['city'], " "))) {
                $this->validate();
                $this->process();
            } else {
                $this->isError = true;
                $this->errorMessage .= ' Fields cannot be empty!';
            }
        }

        if(isset($_POST['addPressed']) && $_POST['addPressed'] == "Add") {
            if(isset($_POST['city']) && !empty(trim($_POST['city'], " "))) {
                header('Location: add');
                exit;
            } else {
                $this->isError = true;
                $this->errorMessage .= ' Fields cannot be empty!';
            }
        }
    }

    public function __destruct() {}

    public function validate() {
        $validator = new Validate;

        $validatedCityName = $validator->validateCity($_POST['city']);

        if($validatedCityName !== false) {
            $this->validatedInput = $validatedCityName;
        } else {
            $this->isError = true;
            $this->errorMessage .= ' The city you entered must be within 3 and 30 characters!';
        }
    }

    public function process() {
        $this->model->populateModel($this->validatedInput);

        $city = $this->model->getCity();
        if($city['queryOK'] === true) {
            if($city['result'][0]['cityName'] == $this->validatedInput) {
                $_SESSION['cityID'] = $city['result'][0]['cityID'];
                $_SESSION['cityName'] = $city['result'][0]['cityName'];

                header('Location: results');
                exit();
            } else {
                $this->isError = true;
                $this->errorMessage .= ' This city does not exist!';
            }
        } else {
            $this->isError = true;
            $this->errorMessage .= ' ' . $city['result'];
        }
    }

    public function getHtmlOutput() {
        if($this->isError) {
            $_SESSION['error'] = $this->errorMessage;

            header('Location: error');
            exit();
        }

        $this->view->createSearchViewPage();
        return $this->view->getHtmlOutput();
    }
}
