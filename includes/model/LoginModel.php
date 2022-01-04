<?php
require_once 'core/Database.php';
require_once 'core/Queries.php';

class LoginModel {
	private $database;
	private $emailAddress;
	private $password;

	public function __construct() {
		$this->database = new Database;
		$this->emailAddress = '';
		$this->password = '';
	}

	public function __destruct() {}

	public function populateModel($validatedInputs) {
		$this->emailAddress = $validatedInputs['emailAddress'];
		$this->password = $validatedInputs['password'];
	}

	public function getUser() {
		$parameters = [
			':emailAddress' => $this->emailAddress,
			':password' => $this->password
		];

		$this->database->executePreparedStatement(Queries::getUser(), $parameters);
		return $this->database->getResult();
	}

}
