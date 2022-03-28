<?php
namespace app\includes\view;

class PageTemplateView {
    protected $htmlOutput;
    protected $htmlTitle;
    protected $htmlContent;
    private $image;

    public function __construct() {
        $this->htmlOutput = '';
        $this->htmlTitle = '';
        $this->htmlContent = '';
        $this->image = 'data:' . 'image/jpg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../includes/images/' . $_SESSION['profilePicture']));
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
            <img src={$this->image}>
            <h1>Welcome back,</h1>
            <h2>{$_SESSION['firstName']}</h2>
        </div>
        <div class="menuItems">
          <form action="add" method="post">
              <input id="addButtonMenu" type="submit" name="addPressed" value="Add">
          </form>
          <form action="search" method="post">
              <input id="searchButtonMenu" type="submit" name="search" value="Search">
          </form>
        </div>
        <form method="post">
          <input id="logoutButtonMenu" type="submit" name="logout" value="Logout"/>
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
