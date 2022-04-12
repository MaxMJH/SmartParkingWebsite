<?php
namespace app\includes\model;

class AddModel {
    /* Fields */
    private $city;
    private $xmlURL;
    private $elements;
    private $executionString;

    /* Constructor and Destructor */
    public function __construct() {
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
