<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class ResultsModel {
    /* Fields */
    private $database;
    private $cityID;
    private $fiveMinutes;
    private $hourly;
    private $daily;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->cityID = -1;
        $this->fiveMinutes = array();
        $this->hourly = array();
        $this->daily = array();
    }

    public function __destruct() {}

    public function populateModel($cityID) {
        $this->cityID = $cityID;
    }

    /* Getters and Setters */
    public function getCityID() {
        return $this->cityID;
    }

    public function setCityID($cityID) {
        $this->cityID = $cityID;
    }

    public function getFiveMinutes() {
        return $this->fiveMinutes;
    }

    public function setFiveMinutes() {
        $parameters = [':cityID' => $this->cityID];

        $this->database->executePreparedStatement(Queries::getFiveMinutes(), $parameters);
        $result = $this->database->getResult();

        if($result['queryOK'] === true) {
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->fiveMinutes, $result['result'][$i]);
            }
        }
    }

    public function getHourly() {
        return $this->hourly;
    }

    public function setHourly() {
        $parameters = [':cityID' => $this->cityID];

        $this->database->executePreparedStatement(Queries::getHourly(), $parameters);
        $result = $this->database->getResult();

        if($result['queryOK'] === true) {
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->hourly, $result['result'][$i]);
            }
        }
    }

    public function getDaily() {
        return $this->daily;
    }

    public function setDaily() {
        $parameters = [':cityID' => $this->cityID];

        $this->database->executePreparedStatement(Queries::getDaily(), $parameters);
        $result = $this->database->getResult();

        if($result['queryOK'] === true) {
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->daily, $result['result'][$i]);
            }
        }
    }
}
