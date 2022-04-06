<?php
namespace app\includes\controller;

use app\includes\view\AddView;
use app\includes\core\Validate;

class AddController {
    private $view;
    private $validatedInputs;
    private $errorMessage;
    private $isError;

    public function __construct() {
        if(!isset($_SESSION['userID']) || isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            $_SESSION = array();

            header('Location: /');
            exit;
        }

        $this->view = new AddView;
        $this->errorMessage = '';
        $this->isError = false;

        if(isset($_POST['addCityPressed']) && $_POST['addCityPressed'] == "Add City") {
            if(isset($_POST['city']) && !empty(trim($_POST['city'], " "))) {
                if(isset($_POST['xmlURL']) && !empty(trim($_POST['xmlURL'], " "))) {
                    if(isset($_POST['elements']) && !empty(trim($_POST['elements'], " "))) {
                        $this->validate();
                        $this->process();
                    }
                }
            } else {
                $this->isError = true;
                $this->errorMessage .= ' Fields cannot be empty!';
            }
        }
    }

    public function __destruct() {}

    public function validate() {
        $validator = new Validate();

        $validatedCityName = $validator->validateCity($_POST['city']);
        $validatedXMLURL = $validator->validateXMLURL($_POST['xmlURL']);
        $validatedElements = $validator->validateElements($_POST['elements']);

        if($validatedCityName !== false && $validatedXMLURL !== false && $validatedElements !== false) {
            $this->validatedInputs['city'] = $validatedCityName;
            $this->validatedInputs['xmlURL'] = $validatedXMLURL;
            $this->validatedInputs['elements'] = $validatedElements;
        } else {
            $this->isError = true;
            $this->errorMessage .= ' Your inputs failed to validate!';
        }
    }

    public function process() {
        $execString = "java -jar /home/pi/XMLScraper/xmlscraper-0.0.1-SNAPSHOT.jar \"{$this->validatedInputs['city']}\" \"carPark\" \"{$this->validatedInputs['xmlURL']}\" {$this->validatedInputs['elements']} > /dev/null &";

        exec($execString);
    }

    public function getHtmlOutput() {
        if($this->isError) {
            $_SESSION['error'] = $this->errorMessage;
            header('Location: error');
            exit();
        }

        $this->view->createSearchViewPage();
        return $this->view->getHtmlOutput();
    }
}
