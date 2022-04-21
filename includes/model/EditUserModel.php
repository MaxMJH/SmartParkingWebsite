<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class EditUserModel {
    /* Fields */
    private $database;
    private $loginModel;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->loginModel = new LoginModel;
    }

    public function __destruct() {}

    /* Methods */
    public function updateUser() {
        $parameters = [
            ':emailAddress' => $this->loginModel->getEmailAddress(),
            ':firstName' => $this->loginModel->getFirstName(),
            ':lastName' => $this->loginModel->getLastName(),
            ':password' => $this->loginModel->getPassword(),
            ':isAdmin' => (int)$this->loginModel->getIsAdmin(),
            ':userID' => (int)$this->loginModel->getUserID()
        ];

        $this->database->executePreparedStatement(Queries::updateUser(), $parameters);

        if($this->database->getResult()['queryOK'] === true) {
            return true;
        }

        return false;
    }

    /* Getters and Setters */
    public function getLoginModel() {
        return $this->loginModel;
    }

    public function setLoginModel($loginModel) {
        $this->loginModel = $loginModel;
    }
}
