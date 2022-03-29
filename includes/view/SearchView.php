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
HTML;

                   $this->listCities();

                   $this->htmlContent .= <<<HTML
  </form>
</main>

HTML;
	  }

          private function listCities() {
              for($i = 0; $i < count($_SESSION['cities']); $i++) {
                  $this->htmlContent .= <<<HTML
    <input type="submit" name="city" value="{$_SESSION['cities'][$i]}"/>
HTML;
              }
          }

	  public function getHtmlOutput() {
		  return $this->htmlOutput;
	  }
}
