<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class LoginModel {
	private $database;
	private $emailAddress;
	private $password;

	public function __construct() {
		$this->database = new Database("mysql:host=192.168.0.69;port=3306;dbname=smartpark;charset=utf8mb4", "test", "test");
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
