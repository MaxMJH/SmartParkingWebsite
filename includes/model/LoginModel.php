<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;
use app\includes\core\Encryption;

/**
 * Model for the Login section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. When a user attempts to login to the web application, this
 * model is the bare bones which stores all inputted data and checks to see if
 * the user exists and is in fact an admin. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class LoginModel
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
     * Variable used to store a user's user ID.
     *
     * @since 0.0.1
     *
     * @var integer $userID The user's user ID.
     */
    private $userID;

    /**
     * Variable used to store a user's email address.
     *
     * @since 0.0.1
     *
     * @var string $emailAddress The user's email address.
     */
    private $emailAddress;

    /**
     * Variable used to store a user's salt.
     *
     * @since 0.0.1
     *
     * @var string $salt The user's salt.
     */
    private $salt;

    /**
     * Variable used to store a user's password.
     *
     * @since 0.0.1
     *
     * @var string $password The user's password.
     */
    private $password;

    /**
     * Variable used to store a user's pepper.
     *
     * @since 0.0.1
     *
     * @var string $pepper The user's pepper.
     */
    private $pepper;

    /**
     * Variable used to store a user's first name.
     *
     * @since 0.0.1
     *
     * @var string $firstName The user's first name.
     */
    private $firstName;

    /**
     * Variable used to store a user's last name.
     *
     * @since 0.0.1
     *
     * @var string $lastName The user's last name.
     */
    private $lastName;

    /**
     * Variable used to store a profile picture.
     *
     * @since 0.0.1
     *
     * @var string $profilePicture The user's profile picture.
     */
    private $profilePicture;

    /**
     * Variable used to store a user's admin status.
     *
     * @since 0.0.1
     *
     * @var boolean $isAdmin The user's admin status.
     */
    private $isAdmin;

    /* Constructor and Destructor */
    /**
     * The constructor of the LoginModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, storing user data).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->userID = -1;
        $this->emailAddress = '';
        $this->salt = '';
        $this->password = '';
        $this->pepper = '';
        $this->firstName = '';
        $this->lastName = '';
        $this->profilePicture = '';
        $this->isAdmin = false;
    }

    /**
     * The destructor of the LoginModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to salt and pepper a plaintext password.
     *
     * This class method aims to salt and pepper an inputted plaintext password
     * from the user. As pre-existing users can login (if they are admins), their
     * salt and pepper should already be set within the database. Therefore, they
     * should be retrieved from the database. If the email address exists, true is
     * returned and the class' password property is set to a hashed value.
     *
     * @since 0.0.1
     *
     * @see app\includes\core\Encryption
     * @return boolean true if a user's email address exists, false if not.
     */
    public function saltAndPepperPassword()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':emailAddress' => $this->emailAddress
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getSaltAndPepper(), $parameters);
        $saltAndPepper = $this->database->getResult();

        // Check to see if the model's email address specified exists within the database.
        if($saltAndPepper['queryOK'] === true) {
            // Gather both the salt and pepper, and pass it to the hashPassword method in the Encryption class.
            $salt = $saltAndPepper['result'][0]['salt'];
            $pepper = $saltAndPepper['result'][0]['pepper'];
            $this->password = Encryption::hashPassword($salt, $this->password, $pepper);

            return true;
        }

        return false;
    }

    /**
     * Class method which aims to populate the rest of the model if the user exists.
     *
     * This class method aims populate the remaninig class properties if the entered
     * username and password exist within the database. If the correct details correlate
     * to an entry, a true boolean is returned, and false if not.
     *
     * @since 0.0.1
     *
     * @return boolean true if a user's email address and password exists, false if not.
     */
    public function populateUser()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':emailAddress' => $this->emailAddress,
            ':password' => $this->password
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getUser(), $parameters);
        $user = $this->database->getResult();

        // Check to see if a user entry is returned.
        if($user['queryOK'] === true) {
            // Populate the remaining properties with the user's information.
            $this->userID = $user['result'][0]['userID'];
            $this->emailAddress = $user['result'][0]['emailAddress'];
            $this->firstName = $user['result'][0]['firstName'];
            $this->lastName = $user['result'][0]['lastName'];
            $this->profilePicture = $user['result'][0]['profilePicture'];
            $this->isAdmin = $user['result'][0]['isAdmin'];

            return true;
        }

        return false;
    }

    /* Getters and Setters */
    /**
     * Class method which aims to return the LoginModel's userID property.
     *
     * @since 0.0.1
     *
     * @return integer Integer representation of the LoginModel's userID property.
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Class method which aims to set the LoginModel's userID property.
     *
     * @since 0.0.1
     *
     * @param integer $userID An integer which contains a user's user ID.
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * Class method which aims to return the LoginModel's emailAddress property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the LoginModel's emailAddress property.
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Class method which aims to set the LoginModel's city property.
     *
     * @since 0.0.1
     *
     * @param string $emailAddress A string which contains a user's email address.
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * Class method which aims to return the LoginModel's salt property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the LoginModel's salt property.
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Class method which aims to set the LoginModel's city property.
     *
     * @since 0.0.1
     *
     * @param string $salt A string which contains a user's salt.
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Class method which aims to return the LoginModel's password property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the LoginModel's password property.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Class method which aims to set the LoginModel's city property.
     *
     * @since 0.0.1
     *
     * @param string $password A string which contains a user's password.
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Class method which aims to return the LoginModel's pepper property.
     *
     * @since 0.0.1
     *
     * @return String String representation of the LoginModel's pepper property.
     */
    public function getPepper()
    {
        return $this->pepper;
    }

    /**
     * Class method which aims to set the LoginModel's pepper property.
     *
     * @since 0.0.1
     *
     * @param string $pepper A string which contains a user's pepper.
     */
    public function setPepper($pepper)
    {
        $this->pepper = $pepper;
    }

    /**
     * Class method which aims to return the LoginModel's firstName property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the LoginModel's firstName property.
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Class method which aims to set the LoginModel's firstName property.
     *
     * @since 0.0.1
     *
     * @param string $firstName A string which contains a user's first name.
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Class method which aims to return the LoginModel's lastName property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the LoginModel's lastName property.
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Class method which aims to set the LoginModel's lastName property.
     *
     * @since 0.0.1
     *
     * @param string $lastName A string which contains a user's last name.
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Class method which aims to return the LoginModel's profilePicture property.
     *
     * @since 0.0.1
     *
     * @return string String representation of the LoginModel's profilePicture property.
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Class method which aims to set the LoginModel's profilePicture property.
     *
     * @since 0.0.1
     *
     * @param string $profilePicture A string which contains a user's profile picture.
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    /**
     * Class method which aims to return the LoginModel's isAdmin property.
     *
     * @since 0.0.1
     *
     * @return boolean Boolean representation of the LoginModel's isAdmin property.
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Class method which aims to set the LoginModel's isAdmin property.
     *
     * @since 0.0.1
     *
     * @param boolean $isAdmin A boolean which contains a user's admin status.
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
}
