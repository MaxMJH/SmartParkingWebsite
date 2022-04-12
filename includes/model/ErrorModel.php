<?php
namespace app\includes\model;

class ErrorModel {
    /* Fields */
    private $errorMessage;

    /* Constructor and Destructor */
    public function __construct() {
        $this->errorMessage = '';
    }

    public function __destruct() {}

    /* Methods */
    public function addErrorMessage($errorMessage) {
        $this->errorMessage .= " {$errorMessage}";
    }

    public function hasErrors() {
        return !empty($this->errorMessage);
    }

    /* Getters and Setters */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }
}
