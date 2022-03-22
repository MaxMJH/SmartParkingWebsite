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
<main>
  <form id="search" action="search" method="post">
    <div id="searchBar">
      <img id="searchBarImage" src="/resources/images/searchIcon.png">
      <div id="searchBarLine"></div>
      <input id="searchInput" type="text" name="city" placeholder="Enter a City"/>
    </div>
    <input id="searchButton" type="submit" name="searchPressed" value="Search"/>
  </form>
</main>

HTML;
	  }

	  public function getHtmlOutput() {
		  return $this->htmlOutput;
	  }
}
