<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\core\Database;
use app\includes\core\Queries;

class TestDatabase extends TestCase
{
    public function testDatabaseConnection()
    {
        $database = new Database;

        $this->assertSame($database->isConnected(), true);
    }

    public function testInsertQueryWithValues()
    {
        $database = new Database;
        $database->executePreparedStatement('INSERT INTO City (City.cityName) VALUES (:cityName)', array(':cityName' => 'Bristol'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testSelectQueryWithValues()
    {
        $database = new Database;
        $database->executePreparedStatement('SELECT City.cityName FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Bristol'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testSelectQueryWithValuesThatDoesNotExist()
    {
        $database = new Database;
        $database->executePreparedStatement('SELECT City.cityName FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Swansea'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], false);
    }

    public function testUpdateQueryWithValues()
    {
        $database = new Database;
        $database->executePreparedStatement('UPDATE City SET City.cityName = :newCityName WHERE City.cityName = :cityName', array(':newCityName' => 'Manchester', ':cityName' => 'Bristol'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testUpdateQueryWithValuesThatDoesNotExist()
    {
        $database = new Database;
        $database->executePreparedStatement('UPDATE City SET City.cityName = :newCityName WHERE City.cityName = :cityName', array(':newCityName' => 'Manchester', ':cityName' => 'Liverpool'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], false);
    }

    public function testDeleteQueryWithValues()
    {
        $database = new Database;
        $database->executePreparedStatement('DELETE FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Manchester'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testDeleteQueryWithValuesThatDoesNotExist()
    {
        $database = new Database;
        $database->executePreparedStatement('DELETE FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Hull'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], false);
    }

    public function testGetUser()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getUser(), array(':emailAddress' => 'johnsmithjds@gmail.com', ':password' => 'bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testUpdateUser() {
        $database = new Database;
        $database->executePreparedStatement(Queries::updateUser(), array(':emailAddress' => 'johnsmithjds@gmail.com', ':firstName' => 'John', ':lastName' => 'Smiths', ':password' => 'bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e', ':isAdmin' => 1, ':userID' => 2));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetSaltAndPepper()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getSaltAndPepper(), array(':emailAddress' => 'johnsmithjds@gmail.com'));
        $result = $database->getResult();

        $salt = $result['result'][0]['salt'];
        $pepper = $result['result'][0]['pepper'];

        $this->assertTrue($salt == '5S7l3KQLZgUQsnDL5pydgbRQh' && $pepper == '78O9o6zM2vKfWFqtu3TsO8Ipu');
    }

    public function testGetCity()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getCity(), array(':cityName' => 'Nottingham'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetCityID()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getCityID(), array(':cityName' => 'Nottingham'));
        $result = $database->getResult();

        $this->assertSame($result['result'][0]['cityID'], '1');
    }

    public function testRemoveCity()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::removeCity(), array(':cityID' => 2));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetCities()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getCities(), null);
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetFiveMinutes()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getFiveMinutes(), array(':cityID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetHourly()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getHourly(), array(':cityID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetDaily()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getDaily(), array(':cityID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetCarparks()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getCarparks(), array(':cityID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testAddScraper()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::addScraper(), array(':processID' => 1, ':cityName' => 'Nottingham', ':cityID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testScraperIsActive()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::scraperisActive(), array(':cityName' => 'Nottingham'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetScrapers()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getScrapers(), null);
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetProcessID()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getProcessID(), array(':cityID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testArchiveScraper()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::archiveScraper(), array(':processID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetUsers()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getUsers(), array(':userID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetReviews()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getReviews(), array(':cityID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testGetAllReviews()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::getAllReviews(), null);
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testRemoveReview()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::removeReview(), array(':reviewID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testEmailAddressExists()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::emailAddressExists(), array(':emailAddress' => 'johnsmithjds@gmail.com'));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }

    public function testRemoveUser()
    {
        $database = new Database;
        $database->executePreparedStatement(Queries::removeUser(), array(':userID' => 1));
        $result = $database->getResult();

        $this->assertSame($result['queryOK'], true);
    }
}
