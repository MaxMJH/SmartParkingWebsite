<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class ScraperModel {
    /* Fields */
    private $database;
    private $scraperCityNames;
    private $scraperProcessIDS;
    private $currentProcessID;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->scraperCityNames = array();
        $this->scraperProcessIDS = array();
        $this->currentProcessID = -1;
    }

    public function __destruct() {}

    /* Methods */
    public function killProcess() {
        if(array_search($this->currentProcessID, $this->scraperProcessIDS) !== false) {
            exec("kill {$this->currentProcessID}");

            $parameters = [
                ':processID' => $this->currentProcessID
            ];

            $this->database->executePreparedStatement(Queries::archiveScraper(), $parameters);
            return true;
        }

        return false;
    }

    public function populateCurrentScrapers() {
        $this->database->executePreparedStatement(Queries::getScrapers(), null);

        $data = $this->database->getResult();

        if($data['queryOK'] === true) {
            for($i = 0; $i < count($data['result']); $i++) {
                array_push($this->scraperCityNames, $data['result'][$i]['cityName']);
                array_push($this->scraperProcessIDS, $data['result'][$i]['processID']);
            }
        }
    }

    /* Getters and Setters */
    public function getScraperCityNames() {
        return $this->scraperCityNames;
    }

    public function getScraperProcessIDS() {
        return $this->scraperProcessIDS;
    }

    public function getCurrentProcessID() {
        return $this->currentProcessID;
    }

    public function setCurrentProcessID($processID) {
        $this->currentProcessID = $processID;
    }
}
