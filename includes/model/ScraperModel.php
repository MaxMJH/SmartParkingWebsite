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
                $cityName = $data['result'][$i]['cityName'];
                $processID = $data['result'][$i]['processID'];

                // Check to see if the process is actually running. If not, archive the process.
                exec("ps aux | grep www-data | grep xmlscraper | grep {$cityName}", $output);
                $process = preg_split('/ +/', $output[0]);

                if($process[13] == 'aux') {
                    // This means that the process is not running, so take the processID and archive it.
                    $parameters = [
                        ':processID' => $processID
                    ];

                    $this->database->executePreparedStatement(Queries::archiveScraper(), $parameters);
                } else {
                    array_push($this->scraperCityNames, $cityName);
                    array_push($this->scraperProcessIDS, $processID);
                }

                // For every exec ran with output, the output needs to be cleared before used again.
                $output = '';
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
