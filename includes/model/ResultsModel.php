<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class ResultsModel {
    private $database;
    private $cityID;

    public function __construct() {
        $this->database = new Database("mysql:host=192.168.0.69;port=3306;dbname=smartpark_v2;charset=utf8mb4", "test", "test");
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
