<?php
require 'PageTemplateView.php';

class AddView extends PageTemplateView {
	public function __construct() {
		parent::__construct();
		$this->createSearchViewContent();
		//unset($_SESSION['error']);
	}

	public function __destruct() {}

	public function createSearchViewPage() {
		$this->htmlTitle = 'Smarter Parking Admin Panel';
		$this->createPageHeader();
		$this->createPageContent();
		$this->createPageFooter();
	}

	private function createSearchViewContent() {
		$this->htmlContent = <<<HTML
<div id="container">
  <div id="add">
    <form action="add" method="post">
      <input id="enterCityBar" type="text" name="city" placeholder="Enter a City"/>
      <input id="enterXMLBar" type="text" name="xml" placeholder="Enter XML URL"/>
      <input id="enterTagsBar" type="text" name="tags" placeholder="Enter Tags"/>
      <input id="addCityButton" type="submit" name="addCityPressed" value="Add City"/>
    </form>
  </div>
</div>
HTML;
	}

	public function getHtmlOutput() {
		return $this->htmlOutput;
	}
}
