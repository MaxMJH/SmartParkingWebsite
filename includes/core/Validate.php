<?php
namespace app\includes\core;

/**
 * Main class which aims to validate all POST inputs.
 *
 * This class is mainly used by the controllers of the web application to ensure that
 * all data entered by a user is safe and can not maliciously affect the web application in
 * any way. Each input field within the web application should have a respective validation method
 * within this class. As web forms can be manipulated (lengths), some methods ensure that the length
 * are within a specific range. Some malicious characters are also stripped from input, such as '<', '>'
 * to prevent XSS, etc...
 *
 * @since 0.0.1
 */
class Validate
{
    /* Constructor and Destructor */
    /**
     * The constructor of the Validate class.
     *
     * As the Validate methods are static, the constructor is left blank.
     *
     * @since 0.0.1
     */
    public function __construct() {}

    /**
     * The destructor of the Validate class.
     *
     * As the Validate methods are static, the destructor is left blank.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Validates an admin's username (email address).
     *
     * This method aims to validate an admin's email address by first checking if
     * the length of the email address is within a specific range and then finally
     * sanitising it using PHP's FILTER_SANITIZE_EMAIL constant.
     *
     * @since 0.0.1
     *
     * @param string $username A string which contains the admin's raw email address.
     * @return bool|string A false boolean will be returned if the email address failed to verify, or a sanitised string containing the admin's email address.
     */
    public static function validateUsername($username)
    {
        // Initially set the sanitised email address to false, indicating that it failed to sanitise.
        $sanitisedUsername = false;

        // As web forms can be manipulated, check the string length server-side.
        if(strlen($username) >= 4 && strlen($username) <= 35) {
            // If the string is in the correct length, sanitise the email to remove any malicious characters.
            $sanitisedUsername = filter_var($username, FILTER_SANITIZE_EMAIL);
        }

        // Return either false or the result of the sanitisation of the string.
        return $sanitisedUsername;
    }

    /**
     * Validates an admin's first name.
     *
     * This method aims to validate an admin's name (first or last) by first checking if
     * the length of the name is within a specific range and then finally
     * sanitising it using PHP's FILTER_SANITIZE_STRING constant.
     *
     * @since 0.0.1
     *
     * @param string $name A string which contains the admin's first or last name.
     * @return bool|string A false boolean will be returned if the name failed to verify, or a sanitised string containing the admin's name.
     */
    public static function validateName($name)
    {
        // Initially set the sanitised name to false, indicating that it failed to sanitise.
        $sanitisedName = false;

        // As web forms can be manipulated, check the string length server-side.
        if(strlen($name) >= 3 && strlen($name) <= 25) {
            // If the string is in the correct length, sanitise the name to remove any malicious characters.
            $sanitisedName = filter_var($name, FILTER_SANITIZE_STRING);
        }

        // Return either false or the result of the sanitisation of the string.
        return $sanitisedName;
    }

    /**
     * Validates an admin's password.
     *
     * This method aims to validate an admin's password by first checking if
     * the length of the password is within a specific range and then finally
     * sanitising it using PHP's FILTER_SANITIZE_STRING constant.
     *
     * @since 0.0.1
     *
     * @param string $password A string which contains the admin's password.
     * @return bool|string A false boolean will be returned if the password failed to verify, or a sanitised string containing the admin's password.
     */
    public static function validatePassword($password)
    {
        // Initially set the sanitised password to false, indicating that it failed to sanitise.
        $sanitisedPassword = false;

        // As web forms can be manipulated, check the string length server-side.
        if(strlen($password) >= 8 && strlen($password) <= 25) {
            // If the string is in the correct length, sanitise the password to remove any malicious characters.
            $sanitisedPassword = filter_var($password, FILTER_SANITIZE_STRING);
        }

        // Return either false or the result of the sanitisation of the string.
        return $sanitisedPassword;
    }

    /**
     * Validates a city's name.
     *
     * This method aims to validate a city's name by first checking if
     * the length of the city's name is within a specific range and then finally
     * sanitising it using PHP's FILTER_SANITIZE_STRING constant.
     *
     * @since 0.0.1
     *
     * @param string $city A string which contains the city's name.
     * @return bool|string A false boolean will be returned if the city's name failed to verify, or a sanitised string containing the city's name.
     */
    public static function validateCity($city)
    {
        // Initially set the sanitised city to false, indicating that it failed to sanitise.
        $sanitisedCityName = false;

        // As web forms can be manipulated, check the string length server-side.
        if(strlen($city) >= 3 && strlen($city) <= 30) {
            // If the string is in the correct length, sanitise the city's name to remove any malicious characters.
            $sanitisedCityName = filter_var($city, FILTER_SANITIZE_STRING);

            // As this city name is being used in an execution string, check to see if any command injection symbols are present.
            if(Validate::isIllegal($sanitisedCityName)) {
                return false;
            }
        }

        // Return either false or the result of the sanitisation of the string.
        return $sanitisedCityName;
    }

    /**
     * Validates a city's ID.
     *
     * This method aims to validate a city's ID by checking if
     * the ID is actually an integer.
     *
     * @since 0.0.1
     *
     * @param string $cityID A string which contains a city's ID.
     * @return bool|int A false boolean will be returned if the city's ID failed to verify, or an integer containing the city's ID.
     */
    public static function validateCityID($cityID) {
        // Initally set the filtered city ID to false, indicating that it failed to filter.
        $filteredCityID = false;

        // Check if the city ID is actually an integer.
        if(filter_var($cityID, FILTER_VALIDATE_INT)) {
            // Set the city ID to an actual int.
            $filteredCityID = filter_var($cityID, FILTER_VALIDATE_INT);
        }

        // Return either false or the result of the filtered city ID.
        return $filteredCityID;
    }

    /**
     * Validates a XML URL.
     *
     * This method aims to validate a XML URL by first checking if
     * the URL is in a valid format and then finally sanitising it using
     * PHP's FILTER_SANITIZE_URL constant.
     *
     * @since 0.0.1
     *
     * @param string $xmlURL A string which contains a XML URL.
     * @return bool|string A false boolean will be returned if the XML URL failed to verify, or a sanitised string containing the XML URL.
     */
    public static function validateXMLURL($xmlURL)
    {
        // Initially set the sanitised XML URL to false, indicating that it failed to sanitise.
        $sanitisedXMLURL = false;

        // First check if the XML URL is in a valid form.
        if(filter_var($xmlURL, FILTER_VALIDATE_URL)) {
            // Then sanitise the URL to remove any malicious characters.
            $sanitisedXMLURL = filter_var($xmlURL, FILTER_SANITIZE_URL);

            // As this XML URL is being used in an execution string, check to see if any command injection symbols are present.
            if(Validate::isIllegal($sanitisedXMLURL)) {
                return false;
            }
        }

        // Return either false or the result of the sanitisation of the string.
        return $sanitisedXMLURL;
    }

    /**
     * Validates XML elements.
     *
     * This method aims to validate XML elements by first checking if the length
     * of the city's name is within a specific range, that the XML elements are
     * in a valid format and then finally sanitising it using PHP's
     * FILTER_SANITIZE_STRING and FILTER_FLAG_NO_ENCODE_QUOTES constants.
     *
     * @since 0.0.1
     *
     * @param string $elements A string which contains all XML elements.
     * @return bool|string A false boolean will be returned if the XML elements failed to verify, or a sanitised string containing the XML elements.
     */
    public static function validateElements($elements)
    {
        // Initially set the sanitised XML elements to false, indicating that it failed to sanitise.
        $sanitisedElements = false;

        // As web forms can be manipulated, check the string length server-side.
        if(strlen($elements) >= 3 && strlen($elements) <= 250) {
            // Then sanitise the XML elements to remove any malicious characters, but ignore quotes.
            $sanitisedElements = filter_var($elements, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            // As the XML elements are being used in an execution string, check to see if any command injection symbols are present.
            if(Validate::isIllegal($sanitisedElements)) {
                return false;
            }
        }

        // Return either false or the result of the sanitisation of the string.
        return $sanitisedElements;
    }

    /**
     * Validates a process ID.
     *
     * This method aims to validate a process ID by first checking that the
     * process ID is in a valid format and then finally sanitising it using PHP's
     * FILTER_SANITIZE_NUMBER_INT constant.
     *
     * @since 0.0.1
     *
     * @param string $processID A string which contains a process ID.
     * @return bool|int A false boolean will be returned if the process ID failed to verify, or a sanitised int containing the process ID.
     */
    public static function validateProcessID($processID)
    {
        // Initially set the sanitised process ID to false, indicating that it failed to sanitise.
        $sanitisedProcessID = false;

        // First check if the process is in a valid form (integer).
        if(filter_var($processID, FILTER_VALIDATE_INT)) {
            // Then sanitise the process ID to remove any malicious characters.
            $sanitisedProcessID = filter_var($processID, FILTER_SANITIZE_NUMBER_INT);
        }

        // Return either false or the result of the sanitisation of the integer.
        return $sanitisedProcessID;
    }

    /**
     * Check if a string has any malicious characters.
     *
     * This method compares each character within a string to an array containing
     * a list of offending characters. These offending characters are concerened with
     * symbols which can allow for command injection. If any offending character is found,
     * a boolean is returned to indicate as such, and should be dealt with accordingly.
     *
     * @since 0.0.1
     *
     * @param string $offendingString A potential malicious string.
     * @return bool A false boolean will be returned if the string did not contain any malicious characters, or true if it did.
     */
    private static function isIllegal($offendingString)
    {
        // Create an array containing all possible malicious characters.
        $offendingChars = array(';', '&', '&&', '|', '||', '`', '(', ')', '#');

        // Go through each character in the string to see if any matches with the offeding characters array.
	      for($i = 0; $i < strlen($offendingString); $i++) {
            if(in_array($offendingString[$i], $offendingChars)) {
                return true;
            }
        }

        return false;
    }
}
