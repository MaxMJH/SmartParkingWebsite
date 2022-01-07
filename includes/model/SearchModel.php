<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class SearchModel {
	private $database;
	private $cityName;

	public function __construct() {
		$this->database = new Database("mysql:host=192.168.0.69;port=3306;dbname=smartpark;charset=utf8mb4", "test", "test");
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
