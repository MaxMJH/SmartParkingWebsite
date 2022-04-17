<?php
namespace app\includes\core;

use PDO;

/**
 * Main database script for the website.
 *
 * This class allows the models to access and store data from the database. The script
 * aims to prevent SQL Injection by using prepared statements so that sensitive data is not
 * exposed by a malicious actor.
 *
 * @since 0.0.1
 */
class Database
{
    /* Properties */
    /**
     * Variable used to store the PDO object of the database.
     *
     * @since 0.0.1
     * @var PDO $database Instance of the PDO class.
     */
    private $database;

    /**
     * Variable used to store prepared statements required to manipulate the database.
     *
     * @since 0.0.1
     * @var PDOStatement $preparedStatement Instance of the PDOStatement class.
     */
    private $preparedStatement;

    /**
     * Variable used to store the result of the execution of a prepared statement.
     *
     * @since 0.0.1
     * @var array $result Instance of the array.
     */
    private $result;

    /* Constructor and Destructor */
    /**
     * The constructor of the Database class.
     *
     * The constructor intialises the required properties to ensure that the
     * class functions in the correct and specified manner (in this instance, acessing and manipulating the database).
     * The constructor also attempts a connection to the database and provides an error if unable to connect.
     *
     * @since 0.0.1
     * @global array $_SESSION Global which stores session data.
     */
    public function __construct()
    {
        $this->preparedStatement = '';
        $this->result = array();

        // Check to see if a connection can be made.
        try {
            $this->connect();
        } catch(PDOException $exception) {
            echo 'Unable to connect to the database!';
        }
    }

    /**
     * The destructor of the Database class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct()
    {
        $this->database = null;
        $this->preparedStatement = null;
    }

    /* Wakeup and Sleep */
    /**
     * The wakeup of the Database class.
     *
     * As the model use the Database class and are being serialised, once unserialised,
     * a wakeup method is needed so that the model can re-connect to the database.
     *
     * @since 0.0.1
     */
    public function __wakeup()
    {
        $this->connect();
    }

    /**
     * The sleep of the Database class.
     *
     * As the model use the Database class and are being serialised, once unserialised,
     * a sleep method is needed.
     *
     * @since 0.0.1
     */
    public function __sleep()
    {
        return array();
    }

    /* Methods */
    /**
     * Executes a specified query and parameters.
     *
     * To prevent SQL Injection, prepared statements are used. A query which is pre-specified
     * by the Queries class, and parameters which are specified in their relevant models are combined
     * to produce a result. This result is stored in the $result property. To ensure that the query was
     * successful, it is crucial to first check the boolean return of 'queryOK'. If true, it's results
     * can be accessed using 'result', followed by the specific row (usually index of 0) and the column name.
     *
     * For example:
     * if($result['queryOK'] === true) {
     *     echo "{$result['result'][0]['cityName']}";
     * }
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     *
     * @param string $query String representing the query of which is to be used to manipulate the database.
     * @param array $parameters An array which contains the parameters needed for the query.
     */
    public function executePreparedStatement($query, $parameters)
    {
        // Try and catch which aims to prevent any crashing exceptions.
        try {
            // Put the query in a prepared statement by preventing SQLi.
            $this->preparedStatement = $this->database->prepare($query);

            // Add the parameters to the prepared statement.
            $this->preparedStatement->execute($parameters);

            // Check to see if 1 row or more has been returned.
            if($this->preparedStatement->rowCount() >= 1) {
                // Set the 'queryOK' to true, indicating the query successfully executed.
                $this->result['queryOK'] = true;

                // Use fetchall incase more than 1 row is returned at a time.
                $this->result['result'] = $this->preparedStatement->fetchall();
            } else {
                // Set the 'queryOK' to false, indicating the query failed to execute.
                $this->result['queryOK'] = false;

                // Check for a specific error code, in this case, a failed query.
                if($this->preparedStatement->errorCode() == 00000) {
                    // Store the result for testing or error modelling purposes.
                    $this->result['result'] = 'The value you entered does not exist!';
                } else {
                    // Store the generic error code.
                    $this->result['result'] = $this->preparedStatement->errorCode();
                }
            }
        } catch(PDOException $exception) {
            // Set the 'queryOK' to false, indicating the query failed to execute.
            $this->result['queryOK'] = false;

            // Store the exceptions error message.
            $this->result['result'] = $exception->getMessage();
        }
    }

    /**
     * Attemps to connect to the database with a pre-defined connection string.
     *
     * Attempts to connect to the database by using a pre-defined connection string.
     * The connecion string contains the database, host IP, port, database name,
     * character set, and login credentials needed.
     *
     * @since 0.0.1
     */
    public function connect()
    {
        // TODO: Move to a file which is not read by GitHub, also change credentials.
        $this->database = new PDO("mysql:host=192.168.0.69;port=3306;dbname=smartpark_v2;charset=utf8mb4", "test", "test");
    }

    /**
     * Checks to see if the database connection is successful / active.
     *
     * A simple query is used to see if the script has successfully connected
     * to the database. If so, true is returned.
     *
     * @since 0.0.1
     *
     * @return bool The connection status.
     */
    public function isConnected()
    {
        // Small query to test if the PDO has successfully connected to the Database.
        $connection = $this->database->query('SELECT 1;');

        return $connection === false ? false : true;
    }

    /* Getters */
    /**
     * Returns the outcome of the prepared statement.
     *
     * After the query and parameters have been set and executed by the database,
     * a result is returned. This result specifies whether or not the statement was
     * successful, as well as any returning data requested by the query.
     *
     * @since 0.0.1
     *
     * @return array An array containing the result of the execution of the prepared statement.
     */
    public function getResult()
    {
        return $this->result;
    }
}
