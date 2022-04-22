<?php
namespace app\includes\model;

use app\includes\core\Database;
use app\includes\core\Queries;

class ReviewModel {
    /* Fields */
    private $database;
    private $reviewsIDS;
    private $carparks;
    private $reviews;
    private $currentReviewID;

    /* Constructor and Destructor */
    public function __construct() {
        $this->database = new Database;
        $this->reviewIDS = array();
        $this->cities = array();
        $this->carparks = array();
        $this->reviews = array();
        $this->currentReviewID = -1;
        $this->populateReviews();
    }

    public function __destruct() {}

    /* Methods */
    public function populateReviews() {
        $this->database->executePreparedStatement(Queries::getAllReviews(), null);

        $data = $this->database->getResult();

        if($data['queryOK'] === true) {
            $this->reviewIDS = array();
            $this->cities = array();
            $this->carparks = array();
            $this->reviews = array();

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
        $_SESSION['reviews'] = serialize($this);
    }

    public function removeReview() {
        $parameters = [
            ':reviewID' => $this->currentReviewID
        ];

        $this->database->executePreparedStatement(Queries::removeReview(), $parameters);
        $result = $this->database->getResult();

        if($result['queryOK'] === true) {
            $reviewIndex = array_search($this->currentReviewID, $this->reviewIDS);
            if($reviewIndex !== false) {
                unset($this->reviewIDS[$reviewIndex]);
                unset($this->cities[$reviewIndex]);
                unset($this->carparks[$reviewIndex]);
                unset($this->reviews[$reviewIndex]);

                $this->reviewIDS = array_values($this->reviewIDS);
                $this->cities = array_values($this->cities);
                $this->carparks = array_values($this->carparks);
                $this->reviews = array_values($this->reviews);

                $_SESSION['reviews'] = serialize($this);

                return true;
            }
            return false;
        }

        return false;
    }

    /* Getters and Setters */
    public function getReviewIDS() {
        return $this->reviewIDS;
    }

    public function getCities() {
        return $this->cities;
    }

    public function getCarparks() {
        return $this->carparks;
    }

    public function getReviews() {
        return $this->reviews;
    }

    public function getCurrentReviewID() {
        return $this->currentReviewID;
    }

    public function setCurrentReviewID($currentReviewID) {
        $this->currentReviewID = $currentReviewID;
    }
}
