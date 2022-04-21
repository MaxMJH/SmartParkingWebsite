<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class SettingsModel {
    /* Fields */
    private $database;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
    }

    public function __destruct() {}

    /* Methods */
    public function updateUser($userModel) {
        $parameters = [
            ':emailAddress' => $userModel->getEmailAddress(),
            ':firstName' => $userModel->getFirstName(),
            ':lastName' => $userModel->getLastName(),
            ':password' => $userModel->getPassword(),
            ':isAdmin' => (int)$userModel->getIsAdmin(),
            ':userID' => (int)$userModel->getUserID()
        ];

        $this->database->executePreparedStatement(Queries::updateUser(), $parameters);
        return $this->database->getResult()['queryOK'];
    }
}
