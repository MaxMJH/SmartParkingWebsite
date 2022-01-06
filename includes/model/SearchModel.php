<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

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
		$parameters = [':cityName' => $this->cityName];

		$this->database->executePreparedStatement(Queries::getCity(), $parameters);
		return $this->database->getResult();
	}
}
