<?php
require 'view/ErrorView.php';

class ErrorController {
	public function __construct() {}

	public function getHtmlOutput() {
		$view = new ErrorView;
		$view->createErrorViewPage();
		return $view->getHtmlOutput();
	}
}
