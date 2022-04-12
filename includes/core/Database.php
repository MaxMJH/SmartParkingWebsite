<?php
namespace app\includes\core;

use PDO;

class Database {
    private $database;
    private $preparedStatement;
    private $result;

    public function __construct() {
        $this->preparedStatement = '';
        $this->result = array();

        try {
            $this->connect();
        } catch(PDOException $exception) {
            $_SESSION['error'] = $exception->getMessage();

            header('Location: error');
            exit;
        }
    }

    public function __destruct() {
        $this->database = null;
        $this->preparedStatement = null;
    }

    public function __wakeup() {
        $this->connect();
    }

    public function __sleep() {
        return array();
    }

    public function getResult() {
        return $this->result;
    }

    public function executePreparedStatement($query, $parameters) {
        try {
            $this->preparedStatement = $this->database->prepare($query);
            $this->preparedStatement->execute($parameters);

            if($this->getRowCount() >= 1) {
                $this->result['queryOK'] = true;
                $this->result['result'] = $this->preparedStatement->fetchall();
            } else {
                $this->result['queryOK'] = false;

                if($this->preparedStatement->errorCode() == 00000) {
                    $this->result['result'] = 'The value you entered does not exist!';
                } else {
                    $this->result['result'] = $this->preparedStatement->errorCode();
                }
            }
        } catch(PDOException $exception) {
            $this->result['queryOK'] = false;
            $this->result['result'] = $exception->getMessage();

            $_SESSION['error'] = $exception->getMessage();

            header('Location: error');
            exit;
        }
    }

    private function getRowCount() {
        return $this->preparedStatement->rowCount();
    }

    public function connect() {
        $this->database = new PDO("mysql:host=192.168.0.69;port=3306;dbname=smartpark_v2;charset=utf8mb4", "test", "test");
    }

    public function isConnected() {
        $connection = $this->database->query('SELECT 1;');

        return $connection === false ? false : true;
    }
}
