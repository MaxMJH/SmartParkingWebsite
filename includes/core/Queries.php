<?php
class Queries {
	public function __construct() {}

	public function __destruct() {}

	public static function getUser() {
		$query = 'SELECT User.userId, User.emailAddress, User.password, User.isAdmin FROM User WHERE User.emailAddress = :emailAddress AND User.password = :password';
		return $query;
	}
}
