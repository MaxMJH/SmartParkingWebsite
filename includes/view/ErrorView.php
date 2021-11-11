<?php
require 'PageTemplateView.php';

class ErrorView extends PageTemplateView {
	public function __construct() {
		parent::__construct();
		$this->createErrorViewContent();
	}

	public function __destruct() {}

	public function createErrorViewPage() {
		$this->htmlTitle = 'Smarter Parking Admin Panel';
		$this->createPageHeader();
		$this->createPageContent();
		$this->createPageFooter();
	}

	public function createErrorViewContent() {
		$this->htmlContent = <<<HTML
<div id="container">
  <div id="error">
    <div id="errorTitle">Error</div>
    <div id="errorLine"></div>
    <div id="errorBox">Error - {$_SESSION['error']}</div>
    <form action="search" method="post">
      <input id="returnButton" type="submit" value="Return"/>
    </form>
  </div>
</div>
HTML;
	}

	public function getHtmlOutput() {
		return $this->htmlOutput;
	}
}
