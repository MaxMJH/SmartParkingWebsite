<?php
require 'core/Database.php';
require 'core/Queries.php';

class LoginModel {
	private $emailAddress;
	private $password;

	public function __construct() {
		$this->emailAddress = '';
		$this->password = '';
	}

	public function __destruct() {}

	public function populateModel($validatedInputs) {
		$this->emailAddress = $validatedInputs['emailAddress'];
		$this->password = $validatedInputs['password'];
	}

	public function getUser() {
		$database = new Database;

		$parameters = [
			':emailAddress' => $this->emailAddress,
			':password' => $this->password
		];

		$database->executePreparedStatement(Queries::getUser(), $parameters);
		return $database->getResult();
	}

}
