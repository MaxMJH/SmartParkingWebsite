<?php
namespace app\includes\view;

class SettingsView extends PageTemplateView {
    public function __construct() {
        parent::__construct();
        $this->createSettingsViewContent();
    }

    public function __destruct() {}

    public function createSettingsViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    private function createSettingsViewContent() {
        $user = unserialize($_SESSION['user']);

        $this->htmlContent = <<<HTML
    <main>
      <div id="settings">
        <form id="settings" action="settings" method="post">
          <div id="firstLastNames">
            <div id="firstName">
              <label for="userFirstName">First Name</label>
              <input id="userFirstName" type="text" name="firstName" value="{$user->getFirstName()}"/>
            </div>
            <div id="lastName">
              <label for="userLastName">Last Name</label>
              <input id="userLastName" type="text" name="lastName" value="{$user->getLastName()}"/>
            </div>
          </div>
          <div id="emailAddress">
            <label for="userEmailAddress">Email Address</label>
            <input id="userEmailAddress" type="text" name="emailAddress" value="{$user->getEmailAddress()}"/>
          </div>
          <div id="passwords">
            <div id="userPassword">
              <label for="userPassword">New Password</label>
              <input id="userPassword" type="password" name="newPassword" placeholder="Enter new password"/>
            </div>
            <div id="confirmUserPassword">
              <label for="confirmUserPassword">Confirm Password</label>
              <input id="confirmUserPassword" type="password" name="confirmNewPassword" placeholder="Confirm new password"/>
            </div>
          </div>
          <input id="updateButton" type="submit" name="updateButton" value="Update"/>
        </form>
      </div>
    </main>

HTML;
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
