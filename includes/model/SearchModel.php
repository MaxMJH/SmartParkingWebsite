<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class SearchModel {
    private $database;
    private $cities;
    private $cityID;

    public function __construct() {
        $this->database = new Database("mysql:host=192.168.0.69;port=3306;dbname=smartpark_v2;charset=utf8mb4", "test", "test");
        $this->cities = array();
        $this->cityID = -1;
    }

    public function __destruct() {}

    public function getCities() {
        return $this->cities;
    }

    public function setCities() {
        $this->database->executePreparedStatement(Queries::getCities(), null);

        $data = $this->database->getResult();

        for($i = 0; $i < count($data['result']); $i++) {
            array_push($this->cities, $data['result'][$i]['cityName']);
        }
    }

    public function setCityID($cityName) {
        $parameters = [':cityName' => $cityName];

        $this->database->executePreparedStatement(Queries::getCity(), $parameters);
        $data = $this->database->getResult();

        if($data['queryOK'] === true) {
            $this->cityID = $data['result'][0]['cityID'];
        }
    }

    public function getCityID() {
        return $this->cityID;
    }
}
