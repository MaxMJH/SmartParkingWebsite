<?php
require_once 'core/Database.php';
require_once 'core/Queries.php';

class SearchModel {
	private $database;
	private $cityName;

	public function __construct() {
		$this->database = new Database;
		$this->cityName = '';
	}

	public function __destruct() {}

	public function populateModel($validatedCityName) {
		$this->cityName = $validatedCityName;
	}

	public function getCity() {
		$parameters = [':cityName' => 'Leicester'];

		$this->database->executePreparedStatement(Queries::getCity(), $parameters);
		return $this->database->getResult();
	}
}
