<?php
namespace app\includes\controller;

use app\includes\view\ErrorView;

class ErrorController {
    private $view;

    public function __construct() {
        if(!isset($_SESSION['userID']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit();
        }

        $this->view = new ErrorView;
    }

    public function __destruct() {}

    public function setErrorMessage($error) {
        $this->view->setErrorMessage($error);
    }

    public function getHtmlOutput() {
        $this->view->createErrorViewPage();
        return $this->view->getHtmlOutput();
    }
}
