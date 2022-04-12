<?php
namespace app\includes\controller;

use app\includes\model\ResultsModel;
use app\includes\view\ResultsView;

class ResultsController {
    private $view;
    private $resultsModel;
    private $city;

    public function __construct() {
        if(!isset($_SESSION['user']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit();
        }

        if(!isset($_SESSION['city'])) {
            header('Location: search');
            exit();
        }

        $this->resultsModel = new ResultsModel;
        $this->city = unserialize($_SESSION['city']);
	$this->processResultsTable();
        $this->view = new ResultsView;
    }

    public function __destruct() {}

    public function processResultsTable() {
        $this->resultsModel->setCityID($this->city->getCityID());
	$this->resultsModel->setFiveMinutes();
        $this->resultsModel->setHourly();
        $this->resultsModel->setDaily();

        $_SESSION['results'] = serialize($this->resultsModel);
    }

    public function getHtmlOutput() {
        $this->view->createResultsViewPage();
        return $this->view->getHtmlOutput();
    }
}
