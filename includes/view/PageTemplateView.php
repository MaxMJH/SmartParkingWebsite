<?php
namespace app\includes\view;

class PageTemplateView {
    protected $htmlOutput;
    protected $htmlTitle;
    protected $htmlContent;
    private $image;
    private $user;

    public function __construct() {
        $this->htmlOutput = '';
        $this->htmlTitle = '';
        $this->htmlContent = '';

        $this->user = unserialize($_SESSION['user']);

        $this->image = 'data:' . 'image/jpg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../includes/images/' . $this->user->getProfilePicture()));
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
        <img src={$this->image} alt="Profile Picture">
        <h1>Welcome back,</h1>
        <h2>{$this->user->getFirstName()} {$this->user->getLastName()}</h2>
      </div>
      <div class="menuItems">
        <form action="add" method="post">
          <input id="addButtonMenu" type="submit" name="addPressed" value="Add City"/>
        </form>
        <hr>
        <form action="remove" method="post">
          <input id="removeButtonMenu" type="submit" name="removePressed" value="Remove City"/>
        </form>
        <hr>
        <form action="search" method="post">
          <input id="searchButtonMenu" type="submit" name="search" value="City Search"/>
        </form>
        <hr>
        <form action="scrapers" method="post">
          <input id="scrapersButtonMenu" type="submit" name="viewScrapers" value="View Scrapers"/>
        </form>
        <hr>
        <form action="settings" method="post">
          <input id="settingsButtonMenu" type="submit" name="settings" value="Settings"/>
        </form>
        <hr>
        <form action="users" method="post">
          <input id="userButtonMenu" type="submit" name="user" value="Users"/>
        </form>
        <hr>
        <form action="reviews" method="post">
          <input id="reviewsButtonMenu" type="submit" name="reviews" value="Reviews"/>
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
