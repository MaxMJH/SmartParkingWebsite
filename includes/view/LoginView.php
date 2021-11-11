<?php
class LoginView {
	private $htmlTitle;
	private $htmlOutput;
	private $errorMessage;

	public function __construct() {
		$this->htmlTitle = '';
		$this->htmlOutput = '';
		$this->errorMessage = '';
	}

	public function __destruct() {}

	public function setErrorMessage($errorMessage) {
		$this->errorMessage = $errorMessage;
	}

	public function createLoginViewPage() {
		$this->htmlTitle = 'Smarter Parking Admin Panel';
		$this->htmlOutput = <<<HTML
<!DOCTYPE html>
<html>
  <head>
    <title>{$this->htmlTitle}</title>
    <link href="/resources/css/style.css" rel="stylesheet" type="text/css"/>
  </head>
  <body>
    <div id="loginPanel">
      <div id="loginPanelName">Admin Panel Login</div>
      <form action="" method="post">
        <div id="loginLine"></div>
        <div id="username">
          <input id="username" type="text" name="username" placeholder=" Email Address"/>
          <img id="usernameImage" src="/resources/images/userIcon.png">
        </div>
        <div id="passwordLine"></div>
        <div id="password">
          <input id="password" type="password" name="password" placeholder=" Password"/>
          <img id="passwordImage" src="/resources/images/passwordIcon.png">
        </div>
        <div id="loginError">{$this->errorMessage}</div>
        <input id="loginButton" type="submit" name="submit" value="Login"/>
      </form>
    </div>
  </body>
</html>
HTML;
	}

	public function getHtmlOutput() {
		return $this->htmlOutput;
	}
}
