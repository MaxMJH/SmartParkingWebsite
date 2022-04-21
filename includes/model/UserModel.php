<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class UserModel {
    /* Fields */
    private $database;
    private $users;
    private $selectedUserID;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->users = array();
        $this->selectedUserID = -1;
        $this->populateCurrentUsers();
    }

    public function __destruct() {}

    /* Methods */
    public function populateCurrentUsers() {
        $user = unserialize($_SESSION['user']);

        $parameters = [
            ':userID' => $user->getUserID()
        ];

        $this->database->executePreparedStatement(Queries::getUsers(), $parameters);
        $users = $this->database->getResult();

        if($users['queryOK'] === true) {
            for($i = 0; $i < count($users['result']); $i++) {
                $login = new LoginModel;
                $login->setUserID($users['result'][$i]['userID']);
                $login->setEmailAddress($users['result'][$i]['emailAddress']);
                $login->setFirstName($users['result'][$i]['firstName']);
                $login->setLastName($users['result'][$i]['lastName']);
                $login->setSalt($users['result'][$i]['salt']);
                $login->setPassword($users['result'][$i]['password']);
                $login->setPepper($users['result'][$i]['pepper']);
                $login->setProfilePicture($users['result'][$i]['profilePicture']);
                $login->setIsAdmin($users['result'][$i]['isAdmin']);

                array_push($this->users, $login);
            }
        }
        $_SESSION['users'] = serialize($this);
    }

    public function deleteUser() {
        $parameters = [
            ':userID' => $this->selectedUserID
        ];

        $userIDIndex = -1;

        for($i = 0; $i < count($this->users); $i++) {
            $user = $this->users[$i];

            if($user->getUserID() == $this->selectedUserID) {
                $userIDIndex = $i;
                break;
            }
        }

        $this->database->executePreparedStatement(Queries::removeUser(), $parameters);
        $result = $this->database->getResult();

        if($result['queryOK'] === true) {
            unset($this->users[$userIDIndex]);
            $this->users = array_values($this->users);
            $_SESSION['users'] = serialize($this);

            return true;
        }

        $_SESSION['users'] = serialize($this);

        return false;
    }

    /* Getters and Setters */
    public function getUsers() {
        return $this->users;
    }

    public function getSelectedUserID() {
        return $this->selectedUserID;
    }

    public function setSelectedUserID($userID) {
        $this->selectedUserID = $userID;
    }
}
