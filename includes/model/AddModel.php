<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class AddModel {
    /* Fields */
    private $database;
    private $city;
    private $xmlURL;
    private $elements;
    private $executionString;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->city = '';
        $this->xmlURL = '';
        $this->elements = '';
        $this->executionString = '';
    }

    public function __destruct() {}

    /* Methods */
    public function constructExecutionString() {
        $this->executionString = "java -jar /home/pi/XMLScraper/xmlscraper-0.0.1-SNAPSHOT.jar \"{$this->city}\" \"carPark\" \"{$this->xmlURL}\" {$this->elements} > /dev/null &";
    }

    public function scraperIsActive() {
        $parameters = [
            ':cityName' => $this->city
        ];

        $this->database->executePreparedStatement(Queries::scraperIsActive(), $parameters);
        $result = $this->database->getResult();

        if($result['queryOK'] === true) {
            if($result['result'][0]['isActive'] == 1) {
                return true;
            }
        }

        return false;
    }

    public function generateScraperRecord() {
        exec("ps aux | grep www-data | grep xmlscraper | grep {$this->city}", $output);

        if(count($output) - 1 == 1) {
            $parameters = [
                ':cityName' => $this->city
            ];

            $this->database->executePreparedStatement(Queries::getCityID(), $parameters);

            $data = $this->database->getResult();

            if($data['queryOK'] === true) {
                $processID = preg_split('/ +/', $output[0])[1];

                $parameters = [
                    ':processID' => $processID,
                    ':cityName' => $this->city,
                    ':cityID' => $data['result'][0]['cityID']
                ];

                $this->database->executePreparedStatement(Queries::addScraper(), $parameters);
            }
        }
    }

    /* Getters and Setters */
    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getXMLURL() {
        return $this->xmlURL;
    }

    public function setXMLURL($xmlURL) {
        $this->xmlURL = $xmlURL;
    }

    public function getElements() {
        return $this->elements;
    }

    public function setElements($elements) {
        $this->elements = $elements;
    }

    public function getExecutionString() {
        return $this->executionString;
    }
}
