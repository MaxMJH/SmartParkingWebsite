<?php
require 'view/AddView.php';
require 'core/Validate.php';

class AddController {
	private $view;
	private $validatedInputs;
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

		$this->view = new AddView;
		//$this->model = new SearchModel;
		$this->errorMessage = '';
		$this->isError = false;

		if(isset($_POST['addCityPressed']) && $_POST['addCityPressed'] == "Add City") {
			if(isset($_POST['city']) && !empty(trim($_POST['city'], " "))) {
				if(isset($_POST['xml']) && !empty(trim($_POST['xml'], " "))) {
					if(isset($_POST['tags']) && !empty(trim($_POST['tags'], " "))) {
						$this->validate();
						$this->process();
					}
				}
			} else {
				$this->isError = true;
				$this->errorMessage .= ' Fields cannot be empty!';
			}
		}
	}

	public function __destruct() {}

	public function validate() {
		$validator = new Validate();

		$validatedCityName = $validator->validateCity($_POST['city']);
		//$validatedXML = $validator->validateXML($_POST['xml'});
		//$validatedTags = $validator->validateTags($_POST['tags']);

		if($validatedCityName !== false) {
			$this->validatedInputs['city'] = $validatedCityName;
			//$this->validatedInputs['xml'] = $validatedXML;
			//$this->validatedInputs['tags'] = $validatedTags;
		} else {
			$this->isError = true;
			$this->errorMessage .= ' The city you entered must be within 3 and 30 characters!';
		}
	}

	public function process() {
		$execString = "java -jar ../../../home/pi/Test/xmlscraper-0.0.1-SNAPSHOT.jar \"{$this->validatedInputs['city']}\" \"carpark\" \"{$_POST['xml']}\" {$_POST['tags']} > /dev/null &";

		exec($execString);
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
