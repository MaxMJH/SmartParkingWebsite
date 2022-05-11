<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Add City section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. The admin can populate this class with data pertaining to
 * a city they wish to add to the database. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class AddModel
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
     * @var string $city The name of the city.
     */
    private $city;

    /**
     * Variable used to store the XML URL of City.
     *
     * @since 0.0.1
     *
     * @var string $xmlURL The XML URL that contains carparking data.
     */
    private $xmlURL;

    /**
     * Variable used to store the XML Elements of City.
     *
     * @since 0.0.1
     *
     * @var string $elements The elements of the XML URL that the admin wishes to scrape.
     */
    private $elements;

    /**
     * Variable used to store execution string for the XML Scraper.
     *
     * @since 0.0.1
     *
     * @var string $executionString An execution string which allows the server to run an XML Scraper on the specified city.
     */
    private $executionString;

    /* Constructor and Destructor */
    /**
     * The constructor of the AddModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, storing city data).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->city = '';
        $this->xmlURL = '';
        $this->elements = '';
        $this->executionString = '';
    }

    /**
     * The destructor of the AddModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to generate an execution string.
     *
     * This class method aims to generate an execution string which creates an
     * XML Scraper which pulls data from a specified XML URL.
     *
     * @since 0.0.1
     */
    public function constructExecutionString()
    {
        // Generate an exeuction string which consists of XML Scraper JAR as well as the city name, XML URL and elements.
        $this->executionString = "java -jar /home/pi/XMLScraper/xmlscraper-0.0.1-SNAPSHOT.jar \"{$this->city}\" \"carPark\" \"{$this->xmlURL}\" {$this->elements} > /dev/null &";
    }

    /**
     * Class method which aims to see if an XML Scraper is already active for a specified city.
     *
     * This class method aims to check if an XML Scraper is already attached to the specified city.
     * This check is carried out by seeing if a scraper is active within the database. A scraper is
     * declared active if it has not yet been archived.
     *
     * @since 0.0.1
     *
     * @return boolean true if a scraper already exists on the city, false if not.
     */
    public function scraperIsActive()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':cityName' => $this->city
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::scraperIsActive(), $parameters);
        $result = $this->database->getResult();

        // Check to see if the result was successful.
        if($result['queryOK'] === true) {
            // Check to see if an XML Scraper is already active on this city.
            if($result['result'][0]['isActive'] == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Class method which aims to add the XML Scraper record to the database.
     *
     * This class method aims to add a record of the XML Scraper to the database.
     * After the execution string is executed, this method analyses all active
     * processes under the Apache user (www-data), and searches for an XML Scraper that
     * is attached to the specified city. If such process exists, it must then be added
     * to the database to prevent other XML Scrapers running on the same city.
     *
     * @since 0.0.1
     *
     * @return boolean true if the XML Scraper process was logged in the database, false if not.
     */
    public function generateScraperRecord()
    {
        // Run the ps aux command to search for any active XML Scrapers for this specific city.
        exec("ps aux | grep www-data | grep xmlscraper | grep {$this->city}", $output);

        // If a process does exist, attempt to add it to the database.
        if(count($output) - 1 == 1) {
            // Set the parameters to be inserted into the prepared statement's query.
            $parameters = [
                ':cityName' => $this->city
            ];

            sleep(3);

            // Execute the prepared statement by allocating parameters to a specific query.
            $this->database->executePreparedStatement(Queries::getCityID(), $parameters);
            $data = $this->database->getResult();

            // Check to see if the city specified exists within the database.
            if($data['queryOK'] === true) {
                // Attempt to grab the XML Scraper's process ID from the processes list.
                $processID = preg_split('/ +/', $output[0])[1];

                // Set the parameters to be inserted into the prepared statement's query.
                $parameters = [
                    ':processID' => $processID,
                    ':cityName' => $this->city,
                    ':cityID' => $data['result'][0]['cityID']
                ];

                // Execute the prepared statement by allocating parameters to a specific query.
                $this->database->executePreparedStatement(Queries::addScraper(), $parameters);

                return true;
            }
        }

        return false;
    }

    /* Getters and Setters */
    /**
     * Class method which aims to return the AddModel's city property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the AddModel's city property.
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Class method which aims to set the AddModel's city property.
     *
     * @since 0.0.1
     *
     * @param string $city A string which contains the name of the city.
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Class method which aims to return the AddModel's xmlURL property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the AddModel's xmlURL property.
     */
    public function getXMLURL()
    {
        return $this->xmlURL;
    }

    /**
     * Class method which aims to set the AddModel's xmlURL property.
     *
     * @since 0.0.1
     *
     * @param string $xmlURL A string which contains the URL of an XML.
     */
    public function setXMLURL($xmlURL)
    {
        $this->xmlURL = $xmlURL;
    }

    /**
     * Class method which aims to return the AddModel's elements property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the AddModel's elements property.
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Class method which aims to set the AddModel's elements property.
     *
     * @since 0.0.1
     *
     * @param string $elements A string which contains the elements of the XML.
     */
    public function setElements($elements)
    {
        $this->elements = $elements;
    }

    /**
     * Class method which aims to return the AddModel's executionString property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the AddModel's executionString property.
     */
    public function getExecutionString()
    {
        return $this->executionString;
    }
}
