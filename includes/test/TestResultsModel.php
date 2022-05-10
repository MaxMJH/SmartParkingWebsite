<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\model\ResultsModel;

class TestResultsModel extends TestCase
{
    public function testResultsModelInstance()
    {
        $resultsModel = null;

        $resultsModel = new ResultsModel;

        $this->assertNotNull($resultsModel);
    }

    public function testSetAndGetFiveMinutes()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(1);

        $resultsModel->setFiveMinutes();
        $this->assertTrue(count($resultsModel->getFiveMinutes()) >= 1);
    }

    public function testSetAndGetFiveMinutesWithNonExistentCity()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(3);

        $resultsModel->setFiveMinutes();
        $this->assertTrue(count($resultsModel->getFiveMinutes()) == 0);
    }

    public function testSetAndGetHourly()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(1);

        $resultsModel->setHourly();
        $this->assertTrue(count($resultsModel->getHourly()) >= 1);
    }

    public function testSetAndGetHourlyWithNonExistentCity()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(3);

        $resultsModel->setHourly();
        $this->assertTrue(count($resultsModel->getHourly()) == 0);
    }

    public function testSetAndGetDaily()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(1);

        $resultsModel->setDaily();
        $this->assertTrue(count($resultsModel->getDaily()) >= 1);
    }

    public function testSetAndGetDailyWithNonExistentCity()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(3);

        $resultsModel->setDaily();
        $this->assertTrue(count($resultsModel->getDaily()) == 0);
    }

    public function testSetAndGetCarparks()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(1);

        $resultsModel->setCarparks();
        $this->assertTrue(count($resultsModel->getCarparks()) >= 1);
    }

    public function testSetAndGetCarparksWithNonExistentCity()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(3);

        $resultsModel->setCarparks();
        $this->assertTrue(count($resultsModel->getCarparks()) == 0);
    }

    public function testSetAndGetReviews()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(1);

        $resultsModel->setReviews();
        $this->assertTrue(count($resultsModel->getReviews()) >= 1);
    }

    public function testSetAndGetReviewsWithNonExistentCity()
    {
        $resultsModel = new ResultsModel;

        $resultsModel->setCityID(3);

        $resultsModel->setReviews();
        $this->assertTrue(count($resultsModel->getReviews()) == 0);
    }
}
