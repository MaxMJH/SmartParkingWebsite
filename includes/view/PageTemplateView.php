<?php
namespace app\includes\view;

class PageTemplateView {
    protected $htmlOutput;
    protected $htmlTitle;
    protected $htmlContent;

    public function __construct() {
        $this->htmlOutput = '';
        $this->htmlTitle = '';
        $this->htmlContent = '';
    }

    public function __destruct() {}

    public function createPageHeader() {
        $htmlOutput = <<<HTML
<!DOCTYPE html>
<html>
  <head>
    <title>{$this->htmlTitle}</title>
    <link href="/resources/css/style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
  <div id="header">
    <div id="headerTitle">Smarter Parking Admin Panel</div>
      <form action="" method="post">
        <input id="logoutButton" type="submit" name="logout" value="Logout"/>
      </form>
    </div>
HTML;
        $this->htmlOutput .= $htmlOutput;
    }

    public function createPageContent() {
        $this->htmlOutput .= $this->htmlContent;
    }

    public function createPageFooter() {
        $htmlOutput = <<<HTML
</body>
</html>
HTML;
        $this->htmlOutput .= $htmlOutput;
    }
}
