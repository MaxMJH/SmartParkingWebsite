<?php
namespace app\includes\view;

class AddView extends PageTemplateView {
    public function __construct() {
        parent::__construct();
        $this->createAddViewContent();
    }

    public function __destruct() {}

    public function createAddViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    private function createAddViewContent() {
        $this->htmlContent = <<<HTML
    <main>
      <div id="addInfo">
        <h1>Add a new city</h1>
        <p>
          If more data has been made available in XML form, you can
          now add the city to the database.
        </p>
        <h2>To add the city, you must do the following steps:</h2>
        <ul>
          <li>Identify and enter the name of the city.</li>
          <li>Identify the XML URL and enter it into the correct field. NOTE - THE URL MUST END IN .XML</li>
          <li>Identify the appropriate elements within the XML. By default there are already pre-defined tags, however, if some tags are missing, you must contact the System Administrator.</li>
        </ul>
      </div>
      <div id="add">
        <form id="addForm" action="add" method="post">
          <input id="city" type="text" name="city" placeholder="Enter a City"/>
          <input id="xmlURL" type="text" name="xmlURL" placeholder="Enter XML URL"/>
          <textarea id="elements" type="text" name="elements" placeholder="Enter Elements">"id" "value" "parkingRecordVersionTime" "parkingNumberOfSpaces" "latitude" "longitude" "parkingNumberOfOccupiedSpaces" "parkingSiteOpeningStatus"</textarea>
          <input id="addCityButton" type="submit" name="addCityPressed" value="Add City"/>
        </form>
      </div>
    </main>

HTML;
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
