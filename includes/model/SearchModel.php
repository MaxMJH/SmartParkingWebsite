<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class SearchModel {
    /* Fields */
    private $database;
    private $cities;
    private $cityID;
    private $cityName;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->cities = array();
        $this->cityID = -1;
        $this->cityName = '';
        $this->setCities();
    }

    public function __destruct() {}

    /* Getters and Setters */
    public function getCities() {
        return $this->cities;
    }

    public function setCities() {
        $this->database->executePreparedStatement(Queries::getCities(), null);

        $data = $this->database->getResult();

        if($data['queryOK'] === true) {
            for($i = 0; $i < count($data['result']); $i++) {
                array_push($this->cities, $data['result'][$i]['cityName']);
            }
        }
	$_SESSION['cities'] = $this->cities;
    }

    public function getCityID() {
        return $this->cityID;
    }

    public function setCityID($cityName) {
        $parameters = [':cityName' => $cityName];

        $this->database->executePreparedStatement(Queries::getCity(), $parameters);
        $data = $this->database->getResult();

        if($data['queryOK'] === true) {
            $this->cityID = $data['result'][0]['cityID'];
        }
    }

    public function getCityName() {
        return $this->cityName;
    }

    public function setCityName($cityName) {
        $this->cityName = $cityName;
    }
}
