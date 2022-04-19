<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class RemoveModel {
    /* Fields */
    private $database;
    private $cityName;
    private $cities;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->cityName = '';
        $this->cities = array();
        $this->setCities();
    }

    public function __destruct() {}

    /* Methods */
    public function removeCity() {
        $parameters = [
            ':cityName' => $this->cityName
        ];

        $this->database->executePreparedStatement(Queries::getCityID(), $parameters);

        $data = $this->database->getResult();

        if($data['queryOK'] === true) {
            $cityID = $data['result'][0]['cityID'];

            $parameters = [
                ':cityID' => $cityID
            ];

            $this->database->executePreparedStatement(Queries::getProcessID(), $parameters);

            $data = $this->database->getResult();

            if($data['queryOK'] === true) {
                $processID = $data['result'][0]['processID'];

                exec("kill {$processID}");
            }

            unset($_SESSION['cities'][array_search($this->cityName, $_SESSION['cities'])]);
            $_SESSION['cities'] = array_values($_SESSION['cities']);
            $this->database->executePreparedStatement(Queries::removeCity(), $parameters);
        }
    }

    /* Getters and Setters */
    public function getCityName() {
        return $this->cityName;
    }

    public function setCityName($cityName) {
        $this->cityName = $cityName;
    }

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
}
