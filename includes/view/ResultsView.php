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
        <input id="searchBar2" type="text" name="city" placeholder="Enter a City"/>
        <img id="searchBarImage2" src="/resources/images/searchIcon.png">
        <input id="searchButton2" type="submit" name="resultsSearch" value="Search"/>
      </div>
    </form>
  </div>
  <div id="results">
    <div id="fiveMinutes">
      <div id="fiveMinutesTitle">Five Minutes</div>
      <div id="fiveMinutesLine"></div>
      <div id="table">
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
      </div>
      <div id="hourly">
      <div id="hourlyTitle">Hourly</div>
      <div id="hourlyLine"></div>
      <div id="table">
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
    </div>
      <div id="daily">
      <div id="dailyTitle">Daily</div>
      <div id="dailyLine"></div>
      <div id="table">
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
    </div>
  </div>
</div>
HTML;
	}

	private function setFiveMinutesTable() {
		if(isset($_SESSION['fiveMinutes'])) {
			for($i = 0; $i < count($_SESSION['fiveMinutes']); $i++) {
				$fiveMinutesID = $_SESSION['fiveMinutes'][$i]['fiveMinutesID'];
				$carparkID = $_SESSION['fiveMinutes'][$i]['carparkID'];
				$recordVersionTime = $_SESSION['fiveMinutes'][$i]['recordVersionTime'];
				$occupiedSpaces = $_SESSION['fiveMinutes'][$i]['occupiedSpaces'];
				$isOpen = $_SESSION['fiveMinutes'][$i]['isOpen'] == 1 ? 'True' : 'False'; // PHP is odd...

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
		if(isset($_SESSION['hourly'])) {
                	for($i = 0; $i < count($_SESSION['hourly']); $i++) {
                        	$hourlyID = $_SESSION['hourly'][$i]['hourlyID'];
                        	$carparkID = $_SESSION['hourly'][$i]['carparkID'];
                        	$recordVersionTime = $_SESSION['hourly'][$i]['recordVersionTime'];
                        	$averageOccupiedSpaces = $_SESSION['hourly'][$i]['averageOccupiedSpaces'];

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
                if(isset($_SESSION['daily'])) {
			for($i = 0; $i < count($_SESSION['daily']); $i++) {
                        	$dailyID = $_SESSION['daily'][$i]['dailyID'];
                        	$carparkID = $_SESSION['daily'][$i]['carparkID'];
                        	$recordVersionTime = $_SESSION['daily'][$i]['recordVersionTime'];
                        	$averageOccupiedSpaces = $_SESSION['daily'][$i]['averageOccupiedSpaces'];

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
