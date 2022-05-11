<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

/**
 * Model for the Review section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. This model is initalised and populated so that an admin can view
 * all reviews within the database. The model also stores the data to the
 * web application's database.
 *
 * @since 0.0.1
 */
class ReviewModel
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
     * Variable used to store the collected review IDs.
     *
     * @since 0.0.1
     *
     * @var array $reviewIDS Review IDs of all reviews.
     */
    private $reviewsIDS;

    /**
     * Variable used to store the collected review's carparks.
     *
     * @since 0.0.1
     *
     * @var array $carparks Carparks of all reviews.
     */
    private $carparks;

    /**
     * Variable used to store the collected reviews.
     *
     * @since 0.0.1
     *
     * @var array $reviews All reviews.
     */
    private $reviews;

    /**
     * Variable used to store the current review ID.
     *
     * @since 0.0.1
     *
     * @var integer $currentReviewID The current review ID.
     */
    private $currentReviewID;

    /* Constructor and Destructor */
    /**
     * The constructor of the ReviewModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, viewing and removing a review).
     * The constructor also initialises a connection to the database so that data stored within
     * an object of this class can be stored in the database.
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->database = new Database;
        $this->reviewIDS = array();
        $this->cities = array();
        $this->carparks = array();
        $this->reviews = array();
        $this->currentReviewID = -1;
        $this->populateReviews();
    }

    /**
     * The destructor of the ReviewModel class.
     *
     * The destructor frees up resources occupied by an instantiation of this class.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Class method which aims to add all reviews to the model.
     *
     * This class method aims to add all reviews to this model so that an admin
     * can view and delete them. Once all reviews are gathered, and their respecitve
     * arrays are filled, the admin is free to view and delete.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     */
    public function populateReviews()
    {
        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::getAllReviews(), null);
        $data = $this->database->getResult();

        // Check to see if there are reviews within the database.
        if($data['queryOK'] === true) {
            // Re-initialise arrays to prevent duplication.
            $this->reviewIDS = array();
            $this->cities = array();
            $this->carparks = array();
            $this->reviews = array();

            // For each review returned, add each column to their appropriate array.
            for($i = 0; $i < count($data['result']); $i++) {
                $reviewID = $data['result'][$i]['reviewID'];
                $city = $data['result'][$i]['cityName'];
                $carparkName = $data['result'][$i]['carparkName'];
                $review = $data['result'][$i]['review'];

                array_push($this->reviewIDS, $reviewID);
                array_push($this->cities, $city);
                array_push($this->carparks, $carparkName);
                array_push($this->reviews, $review);
            }
        }

        // Set a $_SESSION 'reviews' global so that it can be used by the view.
        $_SESSION['reviews'] = serialize($this);
    }

    /**
     * Class method which aims to remove a review from the database.
     *
     * This class method aims to remove a review from the database. Each of the
     * model's arrays are re-indexed to prevent issues with the view and the incorrect
     * review being removed.
     *
     * @since 0.0.1
     *
     * @global array $_SESSION Global which stores session data.
     */
    public function removeReview()
    {
        // Set the parameters to be inserted into the prepared statement's query.
        $parameters = [
            ':reviewID' => $this->currentReviewID
        ];

        // Execute the prepared statement by allocating parameters to a specific query.
        $this->database->executePreparedStatement(Queries::removeReview(), $parameters);
        $result = $this->database->getResult();

        // Check to see if the review was successfully removed from the database.
        if($result['queryOK'] === true) {
            // Get the index of the review which has to be removed.
            $reviewIndex = array_search($this->currentReviewID, $this->reviewIDS);

            // Ensure that the index does in fact exist.
            if($reviewIndex !== false) {
                // Remove the data from a specific index.
                unset($this->reviewIDS[$reviewIndex]);
                unset($this->cities[$reviewIndex]);
                unset($this->carparks[$reviewIndex]);
                unset($this->reviews[$reviewIndex]);

                // Re-index the arrays to prevent issues.
                $this->reviewIDS = array_values($this->reviewIDS);
                $this->cities = array_values($this->cities);
                $this->carparks = array_values($this->carparks);
                $this->reviews = array_values($this->reviews);

                // Set the $_SESSION 'reviews' global to the model.
                $_SESSION['reviews'] = serialize($this);

                return true;
            }
            return false;
        }

        return false;
    }

    /* Getters and Setters */
    /**
     * Class method which aims to return the ReviewModel's reviewIDS property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ReviewModel's reviewIDS property.
     */
    public function getReviewIDS()
    {
        return $this->reviewIDS;
    }

    /**
     * Class method which aims to return the ReviewModel's cities property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ReviewModel's cities property.
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Class method which aims to return the ReviewModel's carparks property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ReviewModel's carparks property.
     */
    public function getCarparks()
    {
        return $this->carparks;
    }

    /**
     * Class method which aims to return the ReviewModel's reviews property.
     *
     * @since 0.0.1
     *
     * @return array Array representation of the ReviewModel's reviews property.
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Class method which aims to return the ReviewModel's currentReviewID property.
     *
     * @since 0.0.1
     *
     * @return integer Integer representation of the ReviewModel's currentReviewID property.
     */
    public function getCurrentReviewID()
    {
        return $this->currentReviewID;
    }

    /**
     * Class method which aims to set the ReviewModel's currentReviewID property.
     *
     * @since 0.0.1
     *
     * @param integer $currentReviewID An integer which represents a review's review ID.
     */
    public function setCurrentReviewID($currentReviewID)
    {
        $this->currentReviewID = $currentReviewID;
    }
}
