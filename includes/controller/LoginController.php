<?php
require 'view/LoginView.php';

class LoginController {
	public function __construct() {
	}

	public function getHtmlOutput() {
		$view = new LoginView;
		$view->createLoginViewPage();
		return $view->getHtmlOutput();
	}
}

