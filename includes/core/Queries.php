<?php
class Queries {
	public function __construct() {}

	public function __destruct() {}

	public static function getUser() {
		$query = 'SELECT users.emailAddress, users.password FROM users WHERE users.emailAddress = :emailAddress AND users.password = :password';
		return $query;
	}
}
