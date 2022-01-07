<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\core\Database;
use app\includes\core\Queries;

class TestDatabase extends TestCase {
	public function testDatabaseConnection() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");

		$this->assertSame($database->isConnected(), true);
	}

	public function testInsertQueryWithValues() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
		$database->executePreparedStatement('INSERT INTO City (City.cityName) VALUES (:cityName)', array(':cityName' => 'Bristol'));
		$result = $database->getResult();

		$this->assertSame($result['queryOK'], true);
	}

	public function testSelectQueryWithValues() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
		$database->executePreparedStatement('SELECT City.cityName FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Bristol'));
		$result = $database->getResult();

		$this->assertSame($result['queryOK'], true);
	}

	public function testSelectQueryWithValuesThatDoesNotExist() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement('SELECT City.cityName FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Swansea'));
                $result = $database->getResult();

		$this->assertSame($result['queryOK'], false);
	}

	public function testUpdateQueryWithValues() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement('UPDATE City SET City.cityName = :newCityName WHERE City.cityName = :cityName', array(':newCityName' => 'Manchester', ':cityName' => 'Bristol'));
                $result = $database->getResult();

		$this->assertSame($result['queryOK'], true);
	}

	public function testUpdateQueryWithValuesThatDoesNotExist() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement('UPDATE City SET City.cityName = :newCityName WHERE City.cityName = :cityName', array(':newCityName' => 'Manchester', ':cityName' => 'Liverpool'));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], false);
	}

	public function testDeleteQueryWithValues() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement('DELETE FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Manchester'));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], true);
	}

	public function testDeleteQueryWithValuesThatDoesNotExist() {
                $database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement('DELETE FROM City WHERE City.cityName = :cityName', array(':cityName' => 'Hull'));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], false);
        }

	public function testGetUser() {
                $database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement(Queries::getUser(), array(':emailAddress' => 'johnsmithjds@gmail.com', ':password' => 'password123'));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], true);
	}

	public function testGetCity() {
		$database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement(Queries::getCity(), array(':cityName' => 'Nottingham'));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], true);
	}

	public function testGetFiveMinutes() {
                $database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement(Queries::getFiveMinutes(), array(':cityID' => 1));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], true);
        }

	public function testGetHourly() {
                $database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement(Queries::getHourly(), array(':cityID' => 1));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], true);
        }

	public function testGetDaily() {
                $database = new Database("mysql:host=192.168.0.69;port=3306;dbname=test_smartpark;charset=utf8mb4", "test", "test");
                $database->executePreparedStatement(Queries::getDaily(), array(':cityID' => 1));
                $result = $database->getResult();

                $this->assertSame($result['queryOK'], true);
        }
}
