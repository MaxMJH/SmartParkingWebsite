<?php
require 'view/ErrorView.php';

class ErrorController {
        private $view;

        public function __construct() {
                $this->view = new ErrorView;
        }

	public function __destruct() {}

	public function getHtmlOutput() {
		$this->view->createErrorViewPage();
		return $this->view->getHtmlOutput();
	}
}
