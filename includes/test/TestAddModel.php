<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\model\AddModel;
use app\includes\core\Database;
use app\includes\core\Queries;

class TestAddModel extends TestCase
{
    public function testAddModelInstance()
    {
        $addModel = null;

        $addModel = new AddModel;

        $this->assertNotNull($addModel);
    }

    public function testConstructExecutionString()
    {
        $addModel = new AddModel;

        $addModel->setCity('Nottingham');
        $addModel->setXMLURL('test.xml');
        $addModel->setElements('"test" "element"');
        $addModel->constructExecutionString();

        $this->assertSame($addModel->getExecutionString(), "java -jar /home/pi/XMLScraper/xmlscraper-0.0.1-SNAPSHOT.jar \"Nottingham\" \"carPark\" \"test.xml\" \"test\" \"element\" > /dev/null &");
    }

    public function testScraperIsActive()
    {
        $addModel = new AddModel;
        $database = new Database;

        $addModel->setCity("Nottingham");

        $database->executePreparedStatement(Queries::addScraper(), array(':processID' => 1, ':cityName' => 'Nottingham', ':cityID' => 1));

        $this->assertSame($addModel->scraperIsActive(), true);
    }

    public function testScraperIsActiveWithNonScraperCity()
    {
        $addModel = new AddModel;
        $database = new Database;

        $addModel->setCity("Leicester");

        $this->assertSame($addModel->scraperIsActive(), false);
    }

    public function testGenerateScraperRecordWithNonXMLScraperActive()
    {
        $addModel = new AddModel;

        $addModel->setCity("Leicester");

        $this->assertSame($addModel->generateScraperRecord(), false);
    }
}
