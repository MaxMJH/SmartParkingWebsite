<?php
namespace app\includes\controller;

use app\includes\view\SearchView;
use app\includes\model\SearchModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;
use app\includes\core\Database;
use app\includes\core\Queries;

class SearchController {
    private $view;
    private $searchModel;
    private $errorModel;

    public function __construct() {
        if(!isset($_SESSION['user']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit;
        }

        $this->searchModel = new SearchModel;
        $this->errorModel = new ErrorModel;
        $this->setupCities();
        $this->view = new SearchView;

        if(isset($_POST['city'])) {
            $this->validate();
            $this->process();
        }
    }

    public function __destruct() {}

    private function setupCities() {
        $this->searchModel->setCities();

        $_SESSION['cities'] = $this->searchModel->getCities();
    }

    public function validate() {
        $validator = new Validate;

        $validatedCityName = $validator->validateCity($_POST['city']);

        if($validatedCityName !== false) {
            $this->searchModel->setCityName($validatedCityName);
        } else {
            $this->errorModel->addErrorMessage('The city entered does not exist!');
        }
    }

    public function process() {
        $this->searchModel->setCityID($this->searchModel->getCityName());

        $_SESSION['city'] = serialize($this->searchModel);

        header('Location: results');
        exit();
    }

    public function getHtmlOutput() {
        if($this->errorModel->hasErrors()) {
            $_SESSION['error'] = serialize($this->errorModel);
            $_SESSION['referrer'] = 'search';

            header('Location: error');
            exit();
        }

        $this->view->createSearchViewPage();
        return $this->view->getHtmlOutput();
    }
}
