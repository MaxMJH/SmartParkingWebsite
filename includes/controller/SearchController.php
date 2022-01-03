<?php
require 'view/SearchView.php';

class SearchController {
	private $view;

	public function __construct() {
		if(!isset($_SESSION['userId'])) {
			header('Location: /');
			exit;
		}

		$this->view = new SearchView;
		session_destroy();
	}

	public function __destruct() {}

	public function getHtmlOutput() {
		$this->view->createSearchViewPage();
		return $this->view->getHtmlOutput();
	}
}
