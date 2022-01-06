<?php
class Database {
	private $database;
	private $preparedStatement;
	private $result;

	public function __construct() {
		$this->preparedStatement = '';
		$this->result = array();

		$pdoDestination = 'mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4';
		$pdoUsername = 'test';
		$pdoPassword = 'test';
		try {
			$this->database = new PDO($pdoDestination, $pdoUsername, $pdoPassword);
		} catch(PDOException $exception) {
			$_SESSION['error'] = $exception->getMessage();
			header('Location: error');
			exit;
		}
	}

	public function __destruct() {
		$this->database = null;
	}

	public function getResult() {
		return $this->result;
	}

	public function executePreparedStatement($query, $parameters) {
		try {
			$this->preparedStatement = $this->database->prepare($query);
			$this->preparedStatement->execute($parameters);

			if($this->getRowCount() >= 1) {
				$this->result['queryOK'] = true;
				$this->result['result'] = $this->preparedStatement->fetchall();
			} else {
				$this->result['queryOK'] = false;

				if($this->preparedStatement->errorCode() == 00000) {
					$this->result['result'] = 'The value you entered does not exist!';
				} else {
					$this->result['result'] = $this->preparedStatement->errorCode();
				}
			}
		} catch(PDOException $exception) {
			$_SESSION['error'] = $exception->getMessage();
			header('Location: error');
			exit;
		}
	}

	private function getRowCount() {
		return $this->preparedStatement->rowCount();
	}
}
