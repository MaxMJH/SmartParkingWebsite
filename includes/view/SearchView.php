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
        if(count($_SESSION['cities']) > 0) {
            for($i = 0; $i < count($_SESSION['cities']); $i++) {
                $this->htmlContent .= <<<HTML
    <input type="submit" name="city" value="{$_SESSION['cities'][$i]}"/>

HTML;
            }
        } else {
            $this->htmlContent .= <<<HTML
    <h1>There are currently no carparks available!</h1>

HTML;
        }
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
