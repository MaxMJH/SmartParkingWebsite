<?php
require_once 'core/Database.php';
require_once 'core/Queries.php';

class ResultsModel {
        private $database;
        private $cityID;

        public function __construct() {
                $this->database = new Database;
                $this->cityID = -1;
        }

        public function __destruct() {}

        public function populateModel($cityID) {
                $this->cityID = $cityID;
        }

        public function getFiveMinutes() {
                $parameters = [':cityID' => $this->cityID];

		$this->database->executePreparedStatement(Queries::getFiveMinutes(), $parameters);
                return $this->database->getResult();
	}

	public function getHourly() {
                $parameters = [':cityID' => $this->cityID];

                $this->database->executePreparedStatement(Queries::getHourly(), $parameters);
                return $this->database->getResult();
        }

	public function getDaily() {
                $parameters = [':cityID' => $this->cityID];

                $this->database->executePreparedStatement(Queries::getDaily(), $parameters);
                return $this->database->getResult();
        }
}





