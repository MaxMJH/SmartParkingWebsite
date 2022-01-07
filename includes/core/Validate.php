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
		}

		return $sanitisedCityName;
	}
}
