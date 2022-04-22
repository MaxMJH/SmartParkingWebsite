<?php
namespace app\includes\view;

class ReviewView extends PageTemplateView {
    public function __construct() {
        parent::__construct();
        $this->createReviewViewContent();
    }

    public function __destruct() {}

    public function createReviewViewPage() {
        $this->htmlTitle = 'Smarter Parking Admin Panel';
        $this->createPageHeader();
        $this->createPageContent();
        $this->createPageFooter();
    }

    public function createReviewViewContent() {
        $this->htmlContent = <<<HTML
    <main>
      <div id="review">
        <h2>Reviews</h2>
        <hr>
        <form id="review" action="reviews" method="post">
          <table>
            <tr>
              <th>Review ID</th>
              <th>City</th>
              <th>Carpark Name</th>
              <th>Review</th>
              <th>Remove Review</th>
            </tr>
HTML;

        $this->listReviews();

        $this->htmlContent .= <<<HTML
          </table>
        </form>
      </div>
    </main>
HTML;
    }

    private function listReviews() {
        $reviews = unserialize($_SESSION['reviews']);

        for($i = 0; $i < count($reviews->getReviewIDS()); $i++) {
            $this->htmlContent .= <<<HTML
          <tr>
            <td>{$reviews->getReviewIDS()[$i]}</td>
            <td>{$reviews->getCities()[$i]}</td>
            <td>{$reviews->getCarparks()[$i]}</td>
            <td>{$reviews->getReviews()[$i]}</td>
            <td>
              <button id="removeReviewButton" type="submit" name="removeReviewPressed" value="{$reviews->getReviewIDS()[$i]}">Remove Review</button>
            </td>
          </tr>
HTML;
        }
    }

    public function getHtmlOutput() {
        return $this->htmlOutput;
    }
}
