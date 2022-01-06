<?php
require 'model/ResultsModel.php';
require 'model/SearchModel.php';
require 'view/ResultsView.php';
require 'core/Validate.php';

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
			exit;
		}

		$isError = false;
		$errorMessage = '';
		$this->resultsModel = new ResultsModel;
		$this->processResultsTable();
		$this->view = new ResultsView;

		if(isset($_POST['resultsSearch']) && $_POST['resultsSearch'] == "Search") {
                        if(isset($_POST['city']) && !empty(trim($_POST['city'], " "))) {
				$this->searchModel = new SearchModel;
                                $this->validate();
                                $this->processSearch();
                        } else {
				$this->isError = true;
                                $this->errorMesssage .= ' Fields cannot be empty!';
			}
                }
	}

	public function __destruct() {}

	public function processResultsTable() {
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

	 public function validate() {
                $validator = new Validate();

                $validatedCityName = $validator->validateCity($_POST['city']);

                if($validatedCityName !== false) {
                        $this->validatedInput = $validatedCityName;
                } else {
			$this->isError = true;
			$this->erorrMessage .= ' The city you entered must be within 3 and 30 characters!';
		}
        }

        public function processSearch() {
                $this->searchModel->populateModel($this->validatedInput);

                $city = $this->searchModel->getCity();
                if($city['queryOK'] === true) {
                        if($city['result'][0]['cityName'] === $this->validatedInput) {
                                $_SESSION['cityID'] = $city['result'][0]['cityID'];
                                $_SESSION['cityName'] = $city['result'][0]['cityName'];
                                header('Location: results');
                                exit();
                        } else {
                                $this->isError = true;
                                $this->errorMessage .= ' ' . 'This city does not exist!';

                       		header('Location: error');
                        	exit();
                        }
                } else {
                        $this->isError = true;
                        $this->errorMessage .= ' ' . $city['result'];
                }
        }

	public function getHtmlOutput() {
		//if($this->isError) {
                //        $_SESSION['error'] = $this->errorMessage;
                //        header('Location: error');
                //        exit();
		//}

		$this->view->createResultsViewPage();
		return $this->view->getHtmlOutput();
	}
}
