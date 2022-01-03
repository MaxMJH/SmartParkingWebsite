<?php
require 'view/ResultsView.php';

class ResultsController {
	private $view;

	public function __construct() {
		$this->view = new ResultsView;
	}

	public function __destruct() {}

	public function getHtmlOutput() {
		$this->view->createResultsViewPage();
		return $this->view->getHtmlOutput();
	}
}
