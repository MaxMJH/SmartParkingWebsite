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
        <div id="parkingData">
          <div id="fiveMinutes">
            <h2>Five Minutes</h2>
            <hr>
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
            </table>
          </div>
          <div id="hourly">
            <h2>Hourly</h2>
            <hr>
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
            <h2>Daily</h2>
            <hr>
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
        <div id="carparksAndReviews">
          <div id="carparks">
            <h2>Carparks</h2>
            <hr>
            <table>
              <tr>
                <th>Carpark ID</th>
                <th>Carpark Name</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Total Spaces</th>
              </tr>

HTML;

        $this->setCarparksTable();
        $this->htmlContent .= <<<HTML
            </table>
          </div>
          <div id="reviews">
            <h2>Reviews</h2>
            <hr>
            <table>
              <tr>
                <th>Review ID</th>
                <th>Review</th>
                <th>Carpark ID</th>
              </tr>

HTML;

        $this->setReviewsTable();
        $this->htmlContent .= <<<HTML
            </table>
          </div>
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

    private function setCarparksTable() {
        if(isset($_SESSION['results'])) {
            $carparks = unserialize($_SESSION['results'])->getCarparks();

            for($i = 0; $i < count($carparks); $i++) {
                $carparkID = $carparks[$i]['carparkID'];
                $carparkName = $carparks[$i]['carparkName'];
                $latitude = $carparks[$i]['latitude'];
                $longitude = $carparks[$i]['longitude'];
                $totalSpaces = $carparks[$i]['totalSpaces'];

                $this->htmlContent .= <<<HTML
              <tr>
                <td>{$carparkID}</td>
                <td>{$carparkName}</td>
                <td>{$latitude}</td>
                <td>{$longitude}</td>
                <td>{$totalSpaces}</td>
              </tr>

HTML;
            }
        }
    }

    private function setReviewsTable() {
        if(isset($_SESSION['results'])) {
            $reviews = unserialize($_SESSION['results'])->getReviews();

            for($i = 0; $i < count($reviews); $i++) {
                $reviewID = $reviews[$i]['reviewID'];
                $review = $reviews[$i]['review'];
                $carparkID = $reviews[$i]['carparkID'];

                $this->htmlContent .= <<<HTML
              <tr>
                <td>{$reviewID}</td>
                <td>{$review}</td>
                <td>{$carparkID}</td>
              </tr>

HTML;
            }
        }
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
