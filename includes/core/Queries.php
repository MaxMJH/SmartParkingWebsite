<?php
namespace app\includes\core;

class Queries {
    public function __construct() {}

    public function __destruct() {}

    public static function getUser() {
        $query = 'SELECT User.userID, User.emailAddress, User.salt, User.password, User.pepper, User.isAdmin FROM User WHERE User.emailAddress = :emailAddress AND User.password = :password;';
        return $query;
    }

    public static function getSaltAndPepper() {
        $query = 'SELECT User.salt, User.pepper FROM User WHERE User.emailAddress = :emailAddress;';
        return $query;
    }

    public static function getCity() {
        $query = 'SELECT City.cityID, City.cityName FROM City WHERE City.cityName = :cityName;';
        return $query;
    }

    public static function getFiveMinutes() {
        $query = 'SELECT FiveMinutes.fiveMinutesID, FiveMinutes.carparkID, FiveMinutes.recordVersionTime, FiveMinutes.occupiedSpaces, FiveMinutes.isOpen FROM FiveMinutes INNER JOIN Carpark ON FiveMinutes.carparkID = Carpark.carparkID INNER JOIN City ON Carpark.cityID = City.cityID WHERE City.cityID = :cityID;';
        return $query;
    }

    public static function getHourly() {
        $query = 'SELECT Hourly.hourlyID, Hourly.carparkID, Hourly.recordVersionTime, Hourly.averageOccupiedSpaces FROM Hourly INNER JOIN Carpark ON Hourly.carparkID = Carpark.carparkID INNER JOIN City ON Carpark.cityID = City.cityID WHERE City.cityID = :cityID;';
        return $query;
    }

    public static function getDaily() {
        $query = 'SELECT Daily.dailyID, Daily.carparkID, Daily.recordVersionTime, Daily.averageOccupiedSpaces FROM Daily INNER JOIN Carpark ON Daily.carparkID = Carpark.carparkID INNER JOIN City ON Carpark.cityID = City.cityID WHERE City.cityID = :cityID;';
        return $query;
    }
}
