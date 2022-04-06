<?php
namespace app\includes\core;

class Validate {
    public function __construct() {}

    public function __destruct() {}

    public function validateUsername($username) {
        $sanitisedUsername = false;

        if(strlen($username) >= 4 && strlen($username) <= 35) {
            $sanitisedUsername = filter_var($username, FILTER_SANITIZE_EMAIL);
        }

        return $sanitisedUsername;
    }

    public function validatePassword($password) {
        $sanitisedPassword = false;

        if(strlen($password) >= 8 && strlen($password) <= 25) {
            $sanitisedPassword = filter_var($password, FILTER_SANITIZE_STRING);
        }

        return $sanitisedPassword;
    }

    public function validateCity($city) {
        $sanitisedCityName = false;

        if(strlen($city) >= 3 && strlen($city) <= 30) {
            $sanitisedCityName = filter_var($city, FILTER_SANITIZE_STRING);

            if($this->isIllegal($sanitisedCityName)) {
                return false;
            }
        }

        return $sanitisedCityName;
    }

    public function validateXMLURL($xmlURL) {
        $sanitisedXMLURL = false;

        if(filter_var($xmlURL, FILTER_VALIDATE_URL)) {
	    $sanitisedXMLURL = filter_var($xmlURL, FILTER_SANITIZE_URL);

            if($this->isIllegal($sanitisedXMLURL)) {
                return false;
            }
        }

        return $sanitisedXMLURL;
    }

    public function validateElements($elements) {
        $sanitisedElements = false;

        if(strlen($elements) >= 3 && strlen($elements) <= 150) {
            $sanitisedElements = filter_var($elements, FILTER_SANITIZE_STRING);

            if($this->isIllegal($sanitisedElements)) {
                return false;
            }
        }

        return $sanitisedElements;
    }

    private function isIllegal($offendingString) {
        $offendingChars = array(';', '&', '&&', '|', '||', '`', '(', ')', '#');

	for($i = 0; $i < strlen($offendingString); $i++) {
            if(in_array($offendingString[$i], $offendingChars)) {
                return true;
            }
        }

        return false;
    }
}
