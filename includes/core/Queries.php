<?php
namespace app\includes\core;

/**
 * Main class which stores all of the queries used for the prepared statements.
 *
 * This class acts as a storage area for all queries needed throughout the web application.
 * As all methods within the class are static, this class' methods can be used at any point
 * throughout the application.
 *
 * @since 0.0.1
 */
class Queries
{
    /* Constructor and Destructor */
    /**
     * The constructor of the Queries class.
     *
     * As the query methods are static, the constructor is left blank.
     *
     * @since 0.0.1
     */
    public function __construct() {}

    /**
     * The destructor of the Queries class.
     *
     * As the query methods are static, the destructor is left blank.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Returns a string containing a query.
     *
     * This query aims to return all User information (admin) for a specified
     * email address and password.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getUser()
    {
        $query = 'SELECT User.userID, User.emailAddress, User.firstName, User.lastName, User.salt, User.password, User.pepper, User.profilePicture, User.isAdmin FROM User WHERE User.emailAddress = :emailAddress AND User.password = :password;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to update a user's (admin) email address, first name, last name
     * and password for a specific user ID.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function updateUser()
    {
        $query = 'UPDATE User SET User.emailAddress = :emailAddress, User.firstName = :firstName, User.lastName = :lastName, User.password = :password, User.isAdmin = :isAdmin WHERE User.userID = :userID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return a specific user's (admin) salt and pepper.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getSaltAndPepper()
    {
        $query = 'SELECT User.salt, User.pepper FROM User WHERE User.emailAddress = :emailAddress;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return a City's name and ID.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getCity()
    {
        $query = 'SELECT City.cityID, City.cityName FROM City WHERE City.cityName = :cityName;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return a specific city's ID.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getCityID()
    {
        $query = 'SELECT City.cityID FROM City WHERE City.cityName = :cityName;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to remove a city from the database. This query should
     * also remove all reference of the city throughout the database (foreign keys).
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function removeCity()
    {
        $query = 'DELETE FROM City WHERE City.cityID = :cityID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return all cities within the database.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getCities()
    {
        $query = 'SELECT City.cityID, City.cityName FROM City;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return all FiveMinutes parking data for a specific city.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getFiveMinutes()
    {
        $query = 'SELECT FiveMinutes.fiveMinutesID, FiveMinutes.carparkID, FiveMinutes.recordVersionTime, FiveMinutes.occupiedSpaces, FiveMinutes.isOpen FROM FiveMinutes INNER JOIN Carpark ON FiveMinutes.carparkID = Carpark.carparkID INNER JOIN City ON Carpark.cityID = City.cityID WHERE City.cityID = :cityID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return all Hourly parking data for a specific city.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getHourly()
    {
        $query = 'SELECT Hourly.hourlyID, Hourly.carparkID, Hourly.recordVersionTime, Hourly.averageOccupiedSpaces FROM Hourly INNER JOIN Carpark ON Hourly.carparkID = Carpark.carparkID INNER JOIN City ON Carpark.cityID = City.cityID WHERE City.cityID = :cityID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return all Daily parking data for a specific city.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getDaily()
    {
        $query = 'SELECT Daily.dailyID, Daily.carparkID, Daily.recordVersionTime, Daily.averageOccupiedSpaces FROM Daily INNER JOIN Carpark ON Daily.carparkID = Carpark.carparkID INNER JOIN City ON Carpark.cityID = City.cityID WHERE City.cityID = :cityID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return all carparks within a specific city.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getCarparks()
    {
        $query = 'SELECT Carpark.carparkID, Carpark.carparkName, Carpark.latitude, Carpark.longitude, Carpark.totalSpaces FROM Carpark WHERE Carpark.cityID = :cityID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to add a XML Scraper's process ID, name of the city which
     * it is attached to as well as the city ID.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function addScraper()
    {
        $query = 'INSERT INTO Scraper (Scraper.processID, Scraper.cityName, Scraper.cityID) VALUES (:processID, :cityName, :cityID);';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to archive a XML Scraper when it is no longer in use.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function archiveScraper()
    {
        $query = 'UPDATE Scraper SET Scraper.isActive = 0 WHERE Scraper.processID = :processID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to see if a city is currently attached to a XML Scraper.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function scraperisActive()
    {
        $query = 'SELECT Scraper.isActive FROM Scraper WHERE Scraper.cityName = :cityName AND Scraper.isActive = 1;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return all XML Scrapers which are active on the PI machine.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getScrapers()
    {
        $query = 'SELECT Scraper.processID, Scraper.cityName FROM Scraper WHERE Scraper.isActive = 1;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return the Process ID of an active XML Scraper attached to a city.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getProcessID()
    {
        $query = 'SELECT Scraper.processID FROM Scraper WHERE Scraper.cityID = :cityID and Scraper.isActive = 1;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to return all users within the database.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function getUsers()
    {
        $query = 'SELECT User.userID, User.emailAddress, User.firstName, User.lastName, User.salt, User.password, User.pepper, User.profilePicture, User.isAdmin FROM User WHERE NOT User.userID = :userID;';
        return $query;
    }

    /**
     * Returns a string containing a query.
     *
     * This query aims to delete a specific user from the database.
     *
     * @since 0.0.1
     *
     * @return string A query.
     */
    public static function removeUser()
    {
        $query = 'DELETE FROM User WHERE User.userID = :userID;';
        return $query;
    }
}
