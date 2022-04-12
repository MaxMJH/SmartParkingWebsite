<?php
namespace app\includes\view;

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
  <main>
    <section id="results">
      <div id="results">
        <div id="fiveMinutes">
          <div id="fiveMinutesTitle">Five Minutes</div>
          <div id="fiveMinutesLine"></div>
          <table>
            <tr>
              <th>ID</th>
              <th>Carpark ID</th>
              <th>Record Time</th>
              <th>Occupied Spaces</th>
              <th>Is Open</th>
            </tr>
HTML;

        $this->setFiveMinutesTable();
        $this->htmlContent .= <<<HTML

          </table
        </div>
      </div>
      <div id="hourly">
        <div id="hourlyTitle">Hourly</div>
        <div id="hourlyLine"></div>
        <table>
          <tr>
            <th>ID</th>
            <th>Carpark ID</th>
            <th>Record Time</th>
            <th>Average Occupied Spaces</th>
          </tr>
HTML;

        $this->setHourlyTable();
        $this->htmlContent .= <<<HTML

        </table>
      </div>
      <div id="daily">
        <div id="dailyTitle">Daily</div>
        <div id="dailyLine"></div>
        <table>
          <tr>
            <th>ID</th>
            <th>Carpark ID</th>
            <th>Record Time</th>
            <th>Average Occupied Spaces</th>
          </tr>
HTML;

        $this->setDailyTable();
        $this->htmlContent .= <<<HTML

          </table>
        </div>
      </div>
      <div id="carparks">
        
      </div>
    </section>
  </main>

HTML;
    }

    private function setFiveMinutesTable() {
        if(isset($_SESSION['results'])) {
            $fiveMinutes = unserialize($_SESSION['results'])->getFiveMinutes();

            for($i = 0; $i < count($fiveMinutes); $i++) {
                $fiveMinutesID = $fiveMinutes[$i]['fiveMinutesID'];
                $carparkID = $fiveMinutes[$i]['carparkID'];
                $recordVersionTime = $fiveMinutes[$i]['recordVersionTime'];
                $occupiedSpaces = $fiveMinutes[$i]['occupiedSpaces'];
                $isOpen = $fiveMinutes[$i]['isOpen'] == 1 ? 'True' : 'False'; // PHP is odd...

                $this->htmlContent .= <<<HTML

          <tr>
            <td>{$fiveMinutesID}</td>
            <td>{$carparkID}</td>
            <td>{$recordVersionTime}</td>
            <td>{$occupiedSpaces}</td>
            <td>{$isOpen}</td>
          </tr>
HTML;
            }
        }
    }

    private function setHourlyTable() {
        if(isset($_SESSION['results'])) {
            $hourly = unserialize($_SESSION['results'])->getHourly();

            for($i = 0; $i < count($hourly); $i++) {
                $hourlyID = $hourly[$i]['hourlyID'];
                $carparkID = $hourly[$i]['carparkID'];
                $recordVersionTime = $hourly[$i]['recordVersionTime'];
                $averageOccupiedSpaces = $hourly[$i]['averageOccupiedSpaces'];

                $this->htmlContent .= <<<HTML

          <tr>
            <td>{$hourlyID}</td>
            <td>{$carparkID}</td>
            <td>{$recordVersionTime}</td>
            <td>{$averageOccupiedSpaces}</td>
          </tr>
HTML;
            }
        }
    }

    private function setDailyTable() {
        if(isset($_SESSION['results'])) {
            $daily = unserialize($_SESSION['results'])->getDaily();

            for($i = 0; $i < count($daily); $i++) {
                $dailyID = $daily[$i]['dailyID'];
                $carparkID = $daily[$i]['carparkID'];
                $recordVersionTime = $daily[$i]['recordVersionTime'];
                $averageOccupiedSpaces = $daily[$i]['averageOccupiedSpaces'];

                $this->htmlContent .= <<<HTML

          <tr>
            <td>{$dailyID}</td>
            <td>{$carparkID}</td>
            <td>{$recordVersionTime}</td>
            <td>{$averageOccupiedSpaces}</td>
          </tr>
HTML;
            }
        }
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
