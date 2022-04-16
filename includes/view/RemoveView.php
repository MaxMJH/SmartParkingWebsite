<?php
namespace app\includes\view;

class RemoveView extends PageTemplateView {
    /* Consuctor and Destructor */
    public function __construct() {
        parent::__construct();
        $this->createRemoveViewContent();
    }

    public function __destruct() {}

    /* Methods */
    public function createRemoveViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    private function createRemoveViewContent() {
        $this->htmlContent = <<<HTML
    <main>
      <form id="remove" action="remove" method="post">
        <div id="cities">

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
        <div class="city">
          <input type="radio" name="city" value="{$_SESSION['cities'][$i]}"/>
          <label for="city">{$_SESSION['cities'][$i]}</label>
        </div>
      </div>
      <input id="removeCityButton" type="submit" name="removePressed" value="Remove City"/>

HTML;
            }
        } else {
            $this->htmlContent .= <<<HTML
          <h1>There are currently no carparks available!</h1>
        </div>

HTML;
        }
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
