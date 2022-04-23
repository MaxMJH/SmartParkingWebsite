<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the User section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. The admin can populate this class with data pertaining to
 * a user they wish to edit. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class UserModel
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
     * Variable used to store the collected users.
     *
     * @since 0.0.1
     *
     * @var array $users All users within the database.
     */
    private $users;

    /**
     * Variable used to store the selected user ID.
     *
     * @since 0.0.1
     *
     * @var integer $selectedUserID The selected user ID.
     */
    private $selectedUserID;

    /* Constructor and Destructor */
    /**
     * The constructor of the UserModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, storing all users).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->users = array();
        $this->selectedUserID = -1;
        $this->populateCurrentUsers();
    }

    /**
     * The destructor of the UserModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to add all users model.
     *
     * This class method aims to add all users to this model so that an admin
     * can view, edit, and delete them. Once all users are gathered, and their respecitve
     * arrays are filled, the admin is free to view, edit and delete.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     */
    public function populateCurrentUsers()
    {
        // Get the current signed in user to exclude them from the array.
        $user = unserialize($_SESSION['user']);

        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':userID' => $user->getUserID()
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getUsers(), $parameters);
        $users = $this->database->getResult();

        if($users['queryOK'] === true) {
            // Add each user to the users array excluding the logged-in user.
            for($i = 0; $i < count($users['result']); $i++) {
                $login = new LoginModel;
                $login->setUserID($users['result'][$i]['userID']);
                $login->setEmailAddress($users['result'][$i]['emailAddress']);
                $login->setFirstName($users['result'][$i]['firstName']);
                $login->setLastName($users['result'][$i]['lastName']);
                $login->setSalt($users['result'][$i]['salt']);
                $login->setPassword($users['result'][$i]['password']);
                $login->setPepper($users['result'][$i]['pepper']);
                $login->setProfilePicture($users['result'][$i]['profilePicture']);
                $login->setIsAdmin($users['result'][$i]['isAdmin']);

                array_push($this->users, $login);
            }
        }
        // Set the $_SESSION 'users' global to the users array.
        $_SESSION['users'] = serialize($this);
    }

    /**
     * Class method which aims to remove a user.
     *
     * This class method aims to remove a user. Each of the
     * model's arrays are re-indexed to prevent issues with the view and the incorrect
     * review being removed.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     * @return boolean true if the user was removed, false if not.
     */
    public function deleteUser()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':userID' => $this->selectedUserID
        ];

        // Variable used to store user's index of their ID within the users array.
        $userIDIndex = -1;

        // Iterate through each user in array and try and find which index they are stored.
        for($i = 0; $i < count($this->users); $i++) {
            if($this->users[$i]->getUserID() == $this->selectedUserID) {
                $userIDIndex = $i;
                break;
            }
        }

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::removeUser(), $parameters);
        $result = $this->database->getResult();

        // Check to see if the user was removed.
        if($result['queryOK'] === true) {
            // Remove the data from a specific index.
            unset($this->users[$userIDIndex]);

            // Re-index the array to prevent issues.
            $this->users = array_values($this->users);

            // Set the $_SESSION 'users' global to the model.
            $_SESSION['users'] = serialize($this);

            return true;
        }

        // Set the $_SESSION 'users' global to the model.
        $_SESSION['users'] = serialize($this);

        return false;
    }

    /* Getters and Setters */
    public function getUsers()
    {
        return $this->users;
    }

    public function getSelectedUserID()
    {
        return $this->selectedUserID;
    }

    public function setSelectedUserID($userID)
    {
        $this->selectedUserID = $userID;
    }
}
