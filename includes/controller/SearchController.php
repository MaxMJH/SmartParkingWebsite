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

        $this->model = new SearchModel;
        $this->setupCities();
        $this->view = new SearchView;
        $this->validatedInput = '';
        $this->errorMessage = '';
        $this->isError = false;

        if(isset($_POST['city'])) {
            // Unset CityID and CityName.
            unset($_SESSION['cityID']);
            unset($_SESSION['cityName']);

            $this->validate();
            $this->process();
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
            $this->errorMessage .= ' The city entered does not exist!';
        }
    }

    public function process() {
        $this->model->setCityID($_POST['city']);

        $_SESSION['cityID'] = $this->model->getCityID();
        $_SESSION['cityName'] = $_POST['city'];

        header('Location: results');
        exit();
    }

    public function setupCities() {
        $this->model->setCities();

        $_SESSION['cities'] = $this->model->getCities();
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
