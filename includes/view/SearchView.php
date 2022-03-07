<?php
namespace app\includes\view;

class SearchView extends PageTemplateView {
	  public function __construct() {
		    parent::__construct();
		    $this->createSearchViewContent();
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
  <div id="search">
    <div id="searchBarLine"></div>
    <form action="search" method="post">
      <div id="searchBar">
        <input id="searchBar" type="text" name="city" placeholder="Enter a City"/>
        <img id="searchBarImage" src="/resources/images/searchIcon.png">
        <input id="searchButton" type="submit" name="searchPressed" value="Search"/>
        <input id="addButton" type="submit" name="addPressed" value="Add"/>
      </div>
    </form>
  </div>
</div>
HTML;
	  }

	  public function getHtmlOutput() {
		  return $this->htmlOutput;
	  }
}
