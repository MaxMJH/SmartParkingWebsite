<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Settings section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. The admin can populate this class with data pertaining to
 * their information which they want to edit. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class SettingsModel
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

    /* Constructor and Destructor */
    /**
     * The constructor of the SettingsModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, storing edited user data).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
    }

    /**
     * The destructor of the SettingsModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to update a user's details.
     *
     * This class method aims to update a user's details specified by the
     * parameter. If the data was updated successfully, then a true boolean is
     * returned.
     *
     * @since 0.0.1
     *
     * @param LoginModel $loginModel Used to read user data.
     * @return boolean true if the user's data was updated, false if not.
     */
    public function updateUser($loginModel)
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':emailAddress' => $loginModel->getEmailAddress(),
            ':firstName' => $loginModel->getFirstName(),
            ':lastName' => $loginModel->getLastName(),
            ':password' => $loginModel->getPassword(),
            ':isAdmin' => (int)$loginModel->getIsAdmin(),
            ':userID' => (int)$loginModel->getUserID()
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::updateUser(), $parameters);

        // Return the result of the update - true if successful, false if failure.
        return $this->database->getResult()['queryOK'];
    }

    /**
     * Class method which aims to see if an email address exists.
     *
     * This class method aims to see if a specified email address already exists
     * within the database. If so, true is returned, if not, false.
     *
     * @since 0.0.1
     *
     * @param string $emailAddress The email address to be checked against the database.
     * @return boolean true if the email address already exists, false if not.
     */
    public function emailAddressExists($emailAddress)
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':emailAddress' => $emailAddress
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::emailAddressExists(), $parameters);
        $result = $this->database->getResult();

        if($result['queryOK'] === true) {
            return true;
        }

        return false;
    }
}
