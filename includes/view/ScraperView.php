<?php
namespace app\includes\view;

class ScraperView extends PageTemplateView {
    public function __construct() {
        parent::__construct();
        $this->createScraperViewContent();
    }

    public function __destruct() {}

    public function createScraperViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    public function createScraperViewContent() {
        $this->htmlContent = <<<HTML
    <main>
      <div id="scrapers">
        <h2>Active Scrapers</h2>
        <hr>
        <form id="scrapers" action="scrapers" method="post">
          <table>
            <tr>
              <th>Process ID</th>
              <th>City Name</th>
              <th>End Process</th>
            </tr>
HTML;

        $this->listProcesses();

        $this->htmlContent .= <<<HTML
          </table>
        </form>
      </div>
    </main>

HTML;
    }

    private function listProcesses() {
        $scraper = unserialize($_SESSION['scraper']);

        $processIDS = $scraper->getScraperProcessIDS();
        $cityNames = $scraper->getScraperCityNames();

        for($i = 0; $i < count($processIDS); $i++) {
            $processID = $processIDS[$i];
            $cityName = $cityNames[$i];

            $this->htmlContent .= <<<HTML

          <tr>
            <td>{$processID}</td>
            <td>{$cityName}</td>
            <td>
              <button id="endProcessButton" type="submit" name="endProcessPressed" value="{$cityName}_{$processID}">End Process</button>
            </td>
          </tr>

HTML;
        }
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
