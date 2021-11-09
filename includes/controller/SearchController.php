<?php
require 'view/SearchView.php';

class SearchController {
	public function __construct() {}

	public function __destruct() {}

	public function getHtmlOutput() {
		$view = new SearchView;
		$view->createSearchViewPage();
		return $view->getHtmlOutput();
	}
}
