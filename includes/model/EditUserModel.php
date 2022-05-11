<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Edit User section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. The admin can populate this class with data pertaining to
 * a user they wish to edit. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class EditUserModel
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
     * Variable used to store the login model.
     *
     * @since 0.0.1
     *
     * @var LoginModel $loginModel Instance of the LoginModel class.
     */
    private $loginModel;

    /* Constructor and Destructor */
    /**
     * The constructor of the EditUserModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, edit a user's details).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->loginModel = new LoginModel;
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
     * Class method which aims to update a user within the database.
     *
     * This class method aims to update a user within the database. From this, an
     * admin can change all data pertaining to a specific user, such as their password,
     * email address, name, etc... Admins also have the ability to make another user an admin.
     *
     * @since 0.0.1
     *
     * @return boolean true if the user's data was updated, false if not.
     */
    public function updateUser()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':emailAddress' => $this->loginModel->getEmailAddress(),
            ':firstName' => $this->loginModel->getFirstName(),
            ':lastName' => $this->loginModel->getLastName(),
            ':password' => $this->loginModel->getPassword(),
            ':isAdmin' => (int)$this->loginModel->getIsAdmin(),
            ':userID' => (int)$this->loginModel->getUserID()
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::updateUser(), $parameters);

        // Check to see if the update was successful.
        if($this->database->getResult()['queryOK'] === true) {
            return true;
        }

        return false;
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

    /* Getters and Setters */
    /**
     * Class method which aims to return the EditUserModel's loginModel property.
     *
     * @since 0.0.1
     *
     * @return LoginModel An instance of the LoginModel.
     */
    public function getLoginModel()
    {
        return $this->loginModel;
    }

    /**
     * Class method which aims to set the EditUserModel's loginModel property.
     *
     * @since 0.0.1
     *
     * @param LoginModel $loginModel An instance of the LoginModel.
     */
    public function setLoginModel($loginModel)
    {
        $this->loginModel = $loginModel;
    }
}
