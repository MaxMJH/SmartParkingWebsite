<?php
namespace app\includes\controller;

use app\includes\model\ResultsModel;
use app\includes\model\SearchModel;
use app\includes\view\ResultsView;
use app\includes\core\Validate;

class ResultsController {
    private $view;
    private $resultsModel;
    private $searchModel;
    private $isError;
    private $errorMessage;

    public function __construct() {
        if(!isset($_SESSION['userID']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit();
        }

        if(!isset($_SESSION['cityID']) || $_SESSION['cityID'] == -1) {
            header('Location: search');
            exit();
        }

        $this->isError = false;
        $this->errorMessage = '';
        $this->resultsModel = new ResultsModel;
	$this->processResultsTable();
        $this->view = new ResultsView;
    }

    public function __destruct() {}

    public function processResultsTable() {
	$_SESSION['fiveMinutes'] = null;
	$_SESSION['hourly'] = null;
	$_SESSION['daily'] = null;

        $this->resultsModel->populateModel($_SESSION['cityID']);

        $fiveMinutes = $this->resultsModel->getFiveMinutes();
        if($fiveMinutes['queryOK'] === true) {
            $fiveArray = array();
            for($i = 0; $i < count($fiveMinutes['result']); $i++) {
                array_push($fiveArray, $fiveMinutes['result'][$i]);
            }
            $_SESSION['fiveMinutes'] = $fiveArray;
        }

        $hourly = $this->resultsModel->getHourly();
        if($hourly['queryOK'] === true) {
            $hourlyArray = array();
            for($i = 0; $i < count($hourly['result']); $i++) {
                array_push($hourlyArray, $hourly['result'][$i]);
            }
            $_SESSION['hourly'] = $hourlyArray;
        }

        $daily = $this->resultsModel->getDaily();
        if($daily['queryOK'] === true) {
            $dailyArray = array();
            for($i = 0; $i < count($daily['result']); $i++) {
                array_push($dailyArray, $daily['result'][$i]);
            }
            $_SESSION['daily'] = $dailyArray;
        }
    }

    public function getHtmlOutput() {
        if($this->isError) {
            $_SESSION['error'] = $this->errorMessage;

            header('Location: error');
            exit();
        }

        $this->view->createResultsViewPage();
        return $this->view->getHtmlOutput();
    }
}
