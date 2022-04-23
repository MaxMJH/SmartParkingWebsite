<?php
namespace app\includes\model;

/**
 * Model for the Error section of the website.
 *
 * This class uses the MVC design pattern to control the way the admin interacts
 * with the website. If an error occurs through the web application's Controllers,
 * this class will be initialised and all errors stored so that the admin can be
 * notified of the issues.
 *
 * @since 0.0.1
 */
class ErrorModel
{
    /* Properties */
    /**
     * Variable used to store the web application's error messages.
     *
     * @since 0.0.1
     *
     * @var string $errorMessage The web application's error messages.
     */
    private $errorMessage;

    /* Constructor and Destructor */
    /**
     * The constructor of the ErrorModel class.
     *
     * The constructor intialises the required properties to ensure that the
     * model functions in the correct and specified manner (in this instance, storing error messages).
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->errorMessage = '';
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
     * Class method which aims add an error message to the model.
     *
     * Rather than having an array to store all error messages, if another error
     * happens it is simply added onto the end of an existing error message.
     *
     * @since 0.0.1
     *
     * @param string $errorMessage A new error message.
     */
    public function addErrorMessage($errorMessage)
    {
        $this->errorMessage .= " {$errorMessage}";
    }

    /**
     * Class method which aims to see if the model has any errors.
     *
     * This class method aims to check if the model has any errors. If so, a boolean
     * is returned indicating as such.
     *
     * @since 0.0.1
     *
     * @return boolean true if erros exist, false if not.
     */
    public function hasErrors()
    {
        return !empty($this->errorMessage);
    }

    /* Getters and Setters */
    /**
     * Class method which aims to return the ErrorModel's errors.
     *
     * @since 0.0.1
     *
     * @return string String representation of the ErrorModel's errors.
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Class method which aims to set the ErrorModels's errorMessage property.
     *
     * @since 0.0.1
     *
     * @param string $errorMessage A string which contains error messages.
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}
