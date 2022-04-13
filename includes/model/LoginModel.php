<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;
use app\includes\core\Encryption;

class LoginModel {
    /* Fields */
    private $database;
    private $userID;
    private $emailAddress;
    private $password;
    private $firstName;
    private $lastName;
    private $profilePicture;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->userID = -1;
        $this->emailAddress = '';
        $this->password = '';
        $this->firstName = '';
        $this->lastName = '';
        $this->profilePicture = '';
    }

    public function __destruct() {}

    /* Methods */
    public function saltAndPepperPassword() {
        $parameters = [
            ':emailAddress' => $this->emailAddress
        ];

        $this->database->executePreparedStatement(Queries::getSaltAndPepper(), $parameters);
        $saltAndPepper = $this->database->getResult();

        if($saltAndPepper['queryOK'] === true) {
            $salt = $saltAndPepper['result'][0]['salt'];
            $pepper = $saltAndPepper['result'][0]['pepper'];
            $this->password = Encryption::hashPassword($salt, $this->password, $pepper);

            return true;
        }

        return false;
    }

    public function populateUser() {
        $parameters = [
            ':emailAddress' => $this->emailAddress,
            ':password' => $this->password
        ];

        $this->database->executePreparedStatement(Queries::getUser(), $parameters);
        $user = $this->database->getResult();

        if($user['queryOK'] === true) {
            $this->userID = $user['result'][0]['userID'];
            $this->emailAddress = $user['result'][0]['emailAddress'];
            $this->firstName = $user['result'][0]['firstName'];
            $this->lastName = $user['result'][0]['lastName'];
            $this->profilePicture = $user['result'][0]['profilePicture'];

            return true;
        }

        return false;
    }

    /* Getters and Setters */
    public function getUserID() {
        return $this->userID;
    }

    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function getEmailAddress() {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getProfilePicture() {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;
    }
}
