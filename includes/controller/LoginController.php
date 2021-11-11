<?php
require 'view/LoginView.php';
require 'core/Validate.php';
require 'model/LoginModel.php';

class LoginController {
	private $validatedInputs;
	private $isError;
	private $errorMessage;

	public function __construct() {
		$this->validatedInputs = array();
		$this->isError = false;
		$this->errorMessage = '';

		if(isset($_POST['submit']) && $_POST['submit'] == 'Login') {
			if(isset($_POST['username']) && isset($_POST['password'])) {
				if(!empty($_POST['username']) && !empty($_POST['password'])) {
					$this->validate();
					$this->process();
				} else {
					$this->isError = true;
					$this->errorMessage = 'Fields cannot be empty!';
				}
			}
		}
	}

	public function __destruct() {}

	public function validate() {
		$validator = new Validate;

		$validatedEmailAddress = $validator->validateUsername($_POST['username']); //
		$validatedPassword = $validator->validatePassword($_POST['password']);

		if($validatedEmailAddress !== false && $validatedPassword !== false) {
			$this->validatedInputs['emailAddress'] = $validatedEmailAddress;
			$this->validatedInputs['password'] = $validatedPassword;
		}
	}

	public function process() {
		$model = new LoginModel;
		$model->populateModel($this->validatedInputs);

		$user = $model->getUser();

		if($user['queryOK'] === true) {
			$_SESSION['emailAddress'] = $this->validatedInputs['emailAddress'];
			$_SESSION['password'] = $this->validatedInputs['password'];
			header('Location: search');
			exit;
		} else {
			$this->isError = true;
			$this->errorMessage = $user['result'];
		}
		//header('Location: search');
		//exit;
	}

	public function getHtmlOutput() {
		$view = new LoginView;

		if($this->isError) {
			$view->setErrorMessage($this->errorMessage);
		}

		$view->createLoginViewPage();
		return $view->getHtmlOutput();
	}
}

