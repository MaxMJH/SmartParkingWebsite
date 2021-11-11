<?php
require 'PageTemplateView.php';

class ResultsView extends PageTemplateView {
	public function __construct() {
		parent::__construct();
		$this->createResultsViewContent();
	}

	public function __destruct() {}

	public function createResultsViewPage() {
		$this->htmlTitle = 'Smarter Parking Admin Panel';
		$this->createPageHeader();
		$this->createPageContent();
		$this->createPageFooter();
	}

	public function createResultsViewContent() {
		$this->htmlContent = <<<HTML
<div id="container">
  <div id="search2">
    <div id="searchBarLine2"></div>
    <form action="results" method="post">
      <div id="searchBar2">
        <input id="searchBar2" type="text" name="search" placeholder="Enter a City"/>
        <img id="searchBarImage2" src="/resources/images/searchIcon.png">
        <input id="searchButton2" type="submit" value="Search"/>
      </div>
    </form>
  </div>
  <div id="results">
    <div id="fiveMinutes">
      <div id="fiveMinutesTitle">Five Minutes</div>
      <div id="fiveMinutesLine"></div>
    </div>
    <div id="hourly">
      <div id="hourlyTitle">Hourly</div>
      <div id="hourlyLine"></div>
    </div>
    <div id="daily">
      <div id="dailyTitle">Daily</div>
      <div id="dailyLine"></div>
    </div>
  </div>
</div>
HTML;
	}

	public function getHtmlOutput() {
		return $this->htmlOutput;
	}
}
