<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Results section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. When a city is selected from the 'Search' page, this model
 * is initalised and populated so that an admin can view all relevant data.
 *
 * @since 0.0.1
 */
class ResultsModel
{
    /* Properties */
    /**
     * Variable used to store an instace of the web application's database.
     *
     * @since 0.0.1
     *
     * @var Database $database Instance of the Database class.
     */
    private $database;

    /**
     * Variable used to store the city ID of City.
     *
     * @since 0.0.1
     *
     * @var integer $cityID The city ID of the city.
     */
    private $cityID;

    /**
     * Variable used to store the collected five minute data of the City.
     *
     * @since 0.0.1
     *
     * @var array $fiveMinutes Five minutes data of the City.
     */
    private $fiveMinutes;

    /**
     * Variable used to store the collected hourly data of the City.
     *
     * @since 0.0.1
     *
     * @var array $hourly Hourly data of the City.
     */
    private $hourly;

    /**
     * Variable used to store the collected daily data of the City.
     *
     * @since 0.0.1
     *
     * @var array $daily Daily data of the City.
     */
    private $daily;

    /**
     * Variable used to store the carparks of the City.
     *
     * @since 0.0.1
     *
     * @var array $carparks Carparks of the City.
     */
    private $carparks;

    /**
     * Variable used to store the reviews of the City.
     *
     * @since 0.0.1
     *
     * @var array $reviews Reviews of the City.
     */
    private $reviews;

    /* Constructor and Destructor */
    /**
     * The constructor of the ResultsModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, viewing city data).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->cityID = -1;
        $this->fiveMinutes = array();
        $this->hourly = array();
        $this->daily = array();
        $this->carparks = array();
        $this->reviews = array();
    }

    /**
     * The destructor of the ResultsModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Getters and Setters */
    /**
     * Class method which aims to return the ResultsModel's cityID property.
     *
     * @since 0.0.1
     *
     * @return integer Integer representation of the ResultsModel's cityID property.
     */
    public function getCityID()
    {
        return $this->cityID;
    }

    /**
     * Class method which aims to set the ResultsModel's cityID property.
     *
     * @since 0.0.1
     *
     * @param integer $cityID An integer which represents a city's city ID.
     */
    public function setCityID($cityID)
    {
        $this->cityID = $cityID;
    }

    /**
     * Class method which aims to return the ResultsModel's fiveMinutes property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ResultsModel's fiveMinutes property.
     */
    public function getFiveMinutes()
    {
        return $this->fiveMinutes;
    }

    /**
     * Class method which aims to set the ResultsModel's fiveMinutes property.
     *
     * @since 0.0.1
     */
    public function setFiveMinutes()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityID' => $this->cityID
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getFiveMinutes(), $parameters);
        $result = $this->database->getResult();

        // Check to see if any FiveMinutes data was returned.
        if($result['queryOK'] === true) {
            // For each entry, add it to the fiveMinutes array.
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->fiveMinutes, $result['result'][$i]);
            }
        }
    }

    /**
     * Class method which aims to return the ResultsModel's hourly property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ResultsModel's hourly property.
     */
    public function getHourly()
    {
        return $this->hourly;
    }

    /**
     * Class method which aims to set the ResultsModel's hourly property.
     *
     * @since 0.0.1
     */
    public function setHourly()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityID' => $this->cityID
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getHourly(), $parameters);
        $result = $this->database->getResult();

        // Check to see if any Hourly data was returned.
        if($result['queryOK'] === true) {
            // For each entry, add it to the hourly array.
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->hourly, $result['result'][$i]);
            }
        }
    }

    /**
     * Class method which aims to return the ResultsModel's daily property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ResultsModel's daily property.
     */
    public function getDaily()
    {
        return $this->daily;
    }

    /**
     * Class method which aims to set the ResultsModel's daily property.
     *
     * @since 0.0.1
     */
    public function setDaily()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityID' => $this->cityID
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getDaily(), $parameters);
        $result = $this->database->getResult();

        // Check to see if any Daily data was returned.
        if($result['queryOK'] === true) {
            // For each entry, add it to the daily array.
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->daily, $result['result'][$i]);
            }
        }
    }

    /**
     * Class method which aims to return the ResultsModel's carparks property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ResultsModel's carparks property.
     */
    public function getCarparks()
    {
        return $this->carparks;
    }

    /**
     * Class method which aims to set the ResultsModel's carparks property.
     *
     * @since 0.0.1
     */
    public function setCarparks()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityID' => $this->cityID
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getCarparks(), $parameters);
        $result = $this->database->getResult();

        // Check to see if any Carparks were returned.
        if($result['queryOK'] === true) {
            // For each entry, add it to the carparks array.
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->carparks, $result['result'][$i]);
            }
        }
    }

    /**
     * Class method which aims to return the ResultsModel's reviews property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ResultsModel's reviews property.
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Class method which aims to set the ResultsModel's reviews property.
     *
     * @since 0.0.1
     */
    public function setReviews()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityID' => $this->cityID
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getReviews(), $parameters);
        $result = $this->database->getResult();

        // Check to see if any reviews were returned.
        if($result['queryOK'] === true) {
            // For each entry, add it to the reviews array.
            for($i = 0; $i < count($result['result']); $i++) {
                array_push($this->reviews, $result['result'][$i]);
            }
        }
    }
}
