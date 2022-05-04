<?php
namespace app\includes\view;

class LoginView extends PageTemplateView {
    public function __construct() {
        $this->htmlTitle = '';
        $this->htmlOutput = '';
    }

    public function __destruct() {}

    public function createLoginViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createLoginViewContent();
        $this->createPageFooter();
    }

    public function createLoginViewContent() {
        $this->htmlOutput = <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$this->htmlTitle}</title>
    <link href="/resources/css/style.css" rel="stylesheet"/>
  </head>
  <body>
    <main>
      <section id="loginPanel">
        <h1>Admin Panel Login</h1>
        <form method="post">
          <div id="usernameSection">
            <img id="userImage" src="/resources/images/userIcon.png" alt="User Icon">
            <div class="line"></div>
            <input id="username" type="text" name="username" placeholder="Email Address"/>
          </div>
          <div id="passwordSection">
            <img id="passwordImage" src="/resources/images/passwordIcon.png" alt="Password Icon">
            <div class="line"></div>
            <input id="password" type="password" name="password" placeholder="Password"/>
          </div>
          <input id="loginButton" type="submit" name="submit" value="Login"/>
        </form>
      </section>
    </main>

HTML;
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
