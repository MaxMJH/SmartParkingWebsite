<?php
require 'view/ResultsView.php';

class ResultsController {
	public function __construct() {}

	public function __destruct() {}

	public function getHtmlOutput() {
		$view = new ResultsView;
		$view->createResultsViewPage();
		return $view->getHtmlOutput();
	}
}
