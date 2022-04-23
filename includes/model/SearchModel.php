<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Search section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. This model is initalised and populated so that an admin can view
 * all cities within the database. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class SearchModel
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
     * Variable used to store all cities within the database.
     *
     * @since 0.0.1
     *
     * @var array $cities All cities within the database.
     */
    private $cities;

    /**
     * Variable used to store the selected city's city ID.
     *
     * @since 0.0.1
     *
     * @var integer $cityID The city's city ID.
     */
    private $cityID;

    /**
     * Variable used to store the selected city's city name.
     *
     * @since 0.0.1
     *
     * @var string $cityName The city's city name.
     */
    private $cityName;

    /* Constructor and Destructor */
    /**
     * The constructor of the SearchModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, storing all cities).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->cities = array();
        $this->cityID = -1;
        $this->cityName = '';
        $this->setCities();
    }

    /**
     * The destructor of the SearchModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Getters and Setters */
    /**
     * Class method which aims to return the SearchModel's cities property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the SearchModel's cities property.
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Class method which aims to set the SearchModel's cities property.
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
            // For each city within the database add it to it's appropriate array.
            for($i = 0; $i < count($data['result']); $i++) {
                array_push($this->cities, $data['result'][$i]['cityName']);
            }
        }
        // Set the $_SESSION 'cities' global to the cities array.
	      $_SESSION['cities'] = $this->cities;
    }

    /**
     * Class method which aims to return the ScraperModel's cityID property.
     *
     * @since 0.0.1
     *
     * @return integer Integer representation of the ScraperModel's cityID property.
     */
    public function getCityID()
    {
        return $this->cityID;
    }

    /**
     * Class method which aims to set the SearchModel's cities property.
     *
     * @since 0.0.1
     *
     * @param string $cityName Used to get a city's city ID.
     */
    public function setCityID($cityName)
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityName' => $cityName
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getCity(), $parameters);
        $data = $this->database->getResult();

        // Check to see if a city was returned, if so, store it's city ID.
        if($data['queryOK'] === true) {
            $this->cityID = $data['result'][0]['cityID'];
        }
    }

    /**
     * Class method which aims to return the SearchModel's cityName property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the SearchModel's cityName property.
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Class method which aims to set the SearchModel's cityName property.
     *
     * @since 0.0.1
     *
     * @param string $cityName Used to set the SearchModel's cityName property.
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }
}
