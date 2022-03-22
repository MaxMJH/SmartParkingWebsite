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
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$this->htmlTitle}</title>
    <link href="/resources/css/style.css" rel="stylesheet">
  </head>
  <body>
    <aside>
        <div class="welcome">
            <img>
            <h1>Welcome back,</h1>
            <h2>{$_SESSION['emailAddress']}</h2>
        </div>
        <form action="add" method="post">
            <input id="addButton" type="submit" name="addPressed" value="Add">
        </form>
        <form method="post">
          <input id="logoutButton" type="submit" name="logout" value="Logout"/>
        </form>
    </aside>

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
