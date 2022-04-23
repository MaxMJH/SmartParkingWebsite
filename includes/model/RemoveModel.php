<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Remove City section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. The admin can populate this class with data pertaining to
 * a city they wish to remove from the database. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class RemoveModel
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
     * Variable used to store the city name of City.
     *
     * @since 0.0.1
     *
     * @var string $cityName The name of the city.
     */
    private $cityName;

    /**
     * Variable used to store an array of cities (city names).
     *
     * @since 0.0.1
     *
     * @var array $cities An array of cities (city names).
     */
    private $cities;

    /* Constructor and Destructor */
    /**
     * The constructor of the RemoveModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, removing a city).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->cityName = '';
        $this->cities = array();
        $this->setCities();
    }

    /**
     * The destructor of the RemoveModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to see remove a city.
     *
     * This class method aims to remove a city from the database as well as the
     * scraper attached to it if available.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     */
    public function removeCity()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityName' => $this->cityName
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getCityID(), $parameters);
        $data = $this->database->getResult();

        // Check to see if the city name exists within the database.
        if($data['queryOK'] === true) {
            // Get the city name's city ID.
            $cityID = $data['result'][0]['cityID'];

            // Set the parameters to be inserted into the prepared statement's query.
            $parameters = [
                ':cityID' => $cityID
            ];

            // Execute the prepared statement by allocating parameters to a specific query.
            $this->database->executePreparedStatement(Queries::getProcessID(), $parameters);
            $data = $this->database->getResult();

            // Check to see if the city being removed has an active scraper attached to it.
            if($data['queryOK'] === true) {
                // Get the city's scraper process ID.
                $processID = $data['result'][0]['processID'];

                // Attempt to kill the scraper.
                exec("kill {$processID}");
            }

            // Remove the city from the $_SESSION 'cities' global array and reset the index values.
            unset($_SESSION['cities'][array_search($this->cityName, $_SESSION['cities'])]);
            $_SESSION['cities'] = array_values($_SESSION['cities']);

            // Finally remove the city from the database.
            $this->database->executePreparedStatement(Queries::removeCity(), $parameters);
        }

        // Unset the $_SESSION 'city' global to prevent the admin from trying to view it's results.
        unset($_SESSION['city']);
    }

    /* Getters and Setters */
    /**
     * Class method which aims to return the RemoveModel's cityName property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the RemoveModels's cityName property.
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Class method which aims to set the RemoveModel's cityName property.
     *
     * @since 0.0.1
     *
     * @param string $cityName A string which contains the name of the city.
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    /**
     * Class method which aims to return the RemoveModel's cities property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the RemoveModel's cities property.
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Class method which aims to set the RemoveModel's cities property.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     */
    public function setCities()
    {
        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getCities(), null);
        $data = $this->database->getResult();

        // Check to see if any cities were returned.
        if($data['queryOK'] === true) {
            // For each city returned add it to the model's cities property.
            for($i = 0; $i < count($data['result']); $i++) {
                array_push($this->cities, $data['result'][$i]['cityName']);
            }
        }
        // Set the $_SESSION 'cities' global containing all cities within the database.
        $_SESSION['cities'] = $this->cities;
    }
}
