<?php
namespace app\includes\view;

class UserView extends PageTemplateView {
    public function __construct() {
        parent::__construct();
        $this->createUserViewContent();
    }

    public function __destruct() {}

    public function createUserViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    public function createUserViewContent() {
        $this->htmlContent = <<<HTML
    <main>
      <div id="users">
        <h2>Users</h2>
        <hr>
        <form id="users" action="users" method="post">
          <table>
            <tr>
              <th>User ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email Address</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
HTML;

        $this->listUsers();

        $this->htmlContent .= <<<HTML
          </table>
        </form>
      </div>
    </main>
HTML;
    }

    private function listUsers() {
        $users = unserialize($_SESSION['users']);

        for($i = 0; $i < count($users->getUsers()); $i++) {
            $user = $users->getUsers()[$i];

            $userID = $user->getUserID();

            $this->htmlContent .= <<<HTML
          <tr>
            <td>{$userID}</td>
            <td>{$user->getFirstName()}</td>
            <td>{$user->getLastName()}</td>
            <td>{$user->getEmailAddress()}</td>
            <td>
              <button id="editUserButton" type="submit" formaction="users-edit" name="editUserPressed" value="{$user->getUserID()}">Edit User</button>
            </td>
            <td>
              <button id="deleteUserButton" type="submit" name="deleteUserPressed" value="{$user->getUserID()}">Delete User</button>
            </td>
          </tr>
HTML;
        }
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
