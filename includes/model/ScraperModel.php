<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Scraper section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. This model is initalised and populated so that an admin can view
 * all active scrapers within the database. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class ScraperModel
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
     * Variable used to store the collected scrapers' city names.
     *
     * @since 0.0.1
     *
     * @var array $scraperCityNames City names of all active scrapers.
     */
    private $scraperCityNames;

    /**
     * Variable used to store the collected scrapers' process IDs.
     *
     * @since 0.0.1
     *
     * @var array $scraperProcessIDS Process IDs of all active scrapers.
     */
    private $scraperProcessIDS;

    /**
     * Variable used to store the current process ID.
     *
     * @since 0.0.1
     *
     * @var integer $currentProcessID The current process ID.
     */
    private $currentProcessID;

    /**
     * Variable used to store the current city name.
     *
     * @since 0.0.1
     *
     * @var string $currentCityName The current city name.
     */
    private $currentCityName;

    /* Constructor and Destructor */
    /**
     * The constructor of the ScraperModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, storing active scrapers).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->scraperCityNames = array();
        $this->scraperProcessIDS = array();
        $this->currentProcessID = -1;
        $this->currentCityName = '';
        $this->populateCurrentScrapers();
    }

    /**
     * The destructor of the ScraperModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to kill a scraper's process.
     *
     * This class method aims to kill a scraper's process. Each of the
     * model's arrays are re-indexed to prevent issues with the view and the incorrect
     * review being removed.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     * @return boolean true if the process was killed, false if not.
     */
    public function killProcess()
    {
        // Check to see if the currente process ID is in within the process IDs.
        if(array_search($this->currentProcessID, $this->scraperProcessIDS) !== false) {
            // Firstly kill the process on the webserver.
            exec("kill {$this->currentProcessID}");

            // Set the parameters to be inserted into the prepared statement's query.
            $parameters = [
                ':processID' => $this->currentProcessID
            ];

            // Remove the data from a specific index.
            unset($this->scraperCityNames[array_search($this->currentCityName, $this->scraperCityNames)]);
            unset($this->scraperProcessIDS[array_search($this->currentProcessID, $this->scraperProcessIDS)]);

            // Re-index the arrays to prevent issues.
            $this->scraperCityNames = array_values($this->scraperCityNames);
            $this->scraperProcessIDS = array_values($this->scraperProcessIDS);

            // Set the $_SESSION 'scraper' global to the model.
            $_SESSION['scraper'] = serialize($this);

            // Finally archice the scraper in the database.
            $this->database->executePreparedStatement(Queries::archiveScraper(), $parameters);
            return true;
        }

        return false;
    }

    /**
     * Class method which aims to add all active scrapers to the model.
     *
     * This class method aims to add all active scrapers to this model so that an admin
     * can view and delete them. Once all active scrapers are gathered, and their respecitve
     * arrays are filled, the admin is free to view and delete.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     */
    public function populateCurrentScrapers()
    {
        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getScrapers(), null);
        $data = $this->database->getResult();

        // Check to see if any rows were returned.
        if($data['queryOK'] === true) {
            // Re-initialise arrays to prevent duplication.
            $this->scraperCityNames = array();
            $this->scraperProcessIDS = array();

            // For each active scraper returned, either archive it or add it to their respective arrays.
            for($i = 0; $i < count($data['result']); $i++) {
                $cityName = $data['result'][$i]['cityName'];
                $processID = $data['result'][$i]['processID'];

                // Check to see if the process is actually running. If not, archive the process.
                exec("ps aux | grep www-data | grep xmlscraper | grep {$cityName}", $output);

                if(!isset($output[1])) {
                    // This means that the process is not running, so take the processID and archive it.
                    $parameters = [
                        ':processID' => $processID
                    ];

                    // Finally archice the scraper.
                    $this->database->executePreparedStatement(Queries::archiveScraper(), $parameters);
                } else {
                    // Add the scraper's city name and process ID to their arrays.
                    array_push($this->scraperCityNames, $cityName);
                    array_push($this->scraperProcessIDS, $processID);
                }

                // For every exec ran with output, the output needs to be cleared before used again.
                $output = '';
            }
        }
        // Set a $_SESSION 'scraper' global so that it can be used by the view.
        $_SESSION['scraper'] = serialize($this);
    }

    /* Getters and Setters */
    /**
     * Class method which aims to return the ScraperModel's scraperCityNames property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ScraperModel's scraperCityNames property.
     */
    public function getScraperCityNames()
    {
        return $this->scraperCityNames;
    }

    /**
     * Class method which aims to return the ScraperModel's scraperProcessIDS property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ScraperModel's scraperProcessIDS property.
     */
    public function getScraperProcessIDS()
    {
        return $this->scraperProcessIDS;
    }

    /**
     * Class method which aims to return the ScraperModel's currentProcessID property.
     *
     * @since 0.0.1
     *
     * @return integer Integer representation of the ScraperModel's currentProcessID property.
     */
    public function getCurrentProcessID()
    {
        return $this->currentProcessID;
    }

    /**
     * Class method which aims to set the ScraperModel's currentProcessID property.
     *
     * @since 0.0.1
     *
     * @param integer $currentProcessID An integer which represents a scrapers's process ID.
     */
    public function setCurrentProcessID($processID)
    {
        $this->currentProcessID = $processID;
    }

    /**
     * Class method which aims to return the ScraperModel's currentCityName property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the ScraperModel's currentCityName property.
     */
    public function getCurrentCityName()
    {
        return $this->currentCityName;
    }

    /**
     * Class method which aims to set the ScraperModel's currentCityName property.
     *
     * @since 0.0.1
     *
     * @param string $currentCityName An integer which represents a scrapers's city name.
     */
    public function setCurrentCityName($cityName)
    {
        $this->currentCityName = $cityName;
    }
}
