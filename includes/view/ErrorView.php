<?php
namespace app\includes\view;

class ErrorView extends PageTemplateView {
    private $errorMessage;

    public function __construct() {
        parent::__construct();
        $this->errorMessage = unserialize($_SESSION['error'])->getErrorMessage();
        $this->createErrorViewContent();
    }

    public function __destruct() {}

    public function createErrorViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    public function createErrorViewContent() {
        $this->htmlContent = <<<HTML
<div id="container">
  <div id="error">
    <div id="errorTitle">Error</div>
    <div id="errorLine"></div>
    <div id="errorBox">Error - {$this->errorMessage}</div>
    <form action="search" method="post">
      <input id="returnButton" type="submit" value="Return"/>
    </form>
  </div>
</div>
HTML;
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
