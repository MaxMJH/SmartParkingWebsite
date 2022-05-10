<?php
namespace app\includes\view;

class EditUserView extends PageTemplateView {
    public function __construct() {
        parent::__construct();
        $this->createEditUserViewContent();
    }

    public function __destruct() {}

    public function createEditUserViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    private function createEditUserViewContent() {
        $user = unserialize($_SESSION['edit-user'])->getLoginModel();

        $isAdmin = '';

        if($user->getIsAdmin()) {
            $isAdmin = '<input id="adminCheckbox" type="checkbox" name="isAdmin" value="isAdmin" checked/>';
        } else {
            $isAdmin = '<input id="adminCheckbox" type="checkbox" name="isAdmin" value="isAdmin"/>';
        }

        $this->htmlContent = <<<HTML
    <main>
      <div id="usersedit">
        <form id="userseditForm" action="users-edit" method="post">
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
            <div id="userPasswordDiv">
              <label for="userPassword">New Password</label>
              <input id="userPassword" type="password" name="newPassword" placeholder="Enter new password"/>
            </div>
            <div id="confirmUserPasswordDiv">
              <label for="confirmUserPassword">Confirm Password</label>
              <input id="confirmUserPassword" type="password" name="confirmNewPassword" placeholder="Confirm new password"/>
            </div>
          </div>
          <div id="admin">
            <div id="isAdmin">
              <label for="adminCheckbox">Is Admin</label>
              {$isAdmin}
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
