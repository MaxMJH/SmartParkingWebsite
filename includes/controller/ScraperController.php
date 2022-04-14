<?php
namespace app\includes\controller;

use app\includes\view\ScraperView;
use app\includes\model\ScraperModel;
use app\includes\model\ErrorModel;
use app\includes\core\Validate;

class ScraperController {
    /* Fields */
    private $view;
    private $scraperModel;
    private $errorModel;

    /* Constructor and Destructor */
    public function __construct() {
        if(!isset($_SESSION['user']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit();
        }

        $this->scraperModel = new ScraperModel;
        $this->errorModel = new ErrorModel;
        $this->scraperModel->populateCurrentScrapers();
        $_SESSION['scraper'] = serialize($this->scraperModel);

        if(isset($_POST['endProcessPressed']) && $_POST['endProcessPressed'] == "End Process") {
            $this->validate();
            $this->process();
        }

        $this->view = new ScraperView;
    }

    public function __destruct() {}

    /* Methods */
    public function validate() {
        $validator = new Validate;

        $validatedProcessID = $validator->validateProcessID($_POST['processID']);

        if($validatedProcessID !== false) {
            $this->scraperModel->setCurrentProcessID($validatedProcessID);
        } else {
            $this->errorModel->addErrorMessage('Unable to validate process ID!');
        }
    }

    public function process() {
        if($this->scraperModel->killProcess()) {
            $_SESSION['scraper'] = serialize($this->scraperModel);

            header('Location: scrapers');
            exit();
        } else {
            $this->errorModel->addErrorMessage('Unable to kill process!');
        }
    }

    public function getHtmlOutput() {
        if($this->errorModel->hasErrors()) {
            $_SESSION['error'] = serialize($this->errorModel);

            header('Location: error');
            exit();
        }

        $this->view->createScraperViewPage();
        return $this->view->getHtmlOutput();
    }
}
