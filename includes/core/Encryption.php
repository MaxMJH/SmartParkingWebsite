<?php
namespace app\includes\core;

class Encryption {
        public function __construct() {}

        public function __destruct() {}

        public static function hashPassword($salt, $password, $pepper) {
		$spPassword = $salt . $password . $pepper;
		return hash("sha256", $spPassword);
	}
}

