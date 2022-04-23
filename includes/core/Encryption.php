<?php
namespace app\includes\core;

/**
 * Class which stores Encryption methods.
 *
 * This class is used to store any used encryption methods throughout the
 * web application. One example is SHA-256 hashing.
 *
 * @since 0.0.1
 */
class Encryption
{
    /* Constructor and Destructor */
    /**
     * The constructor of the Encryption class.
     *
     * As the main encryption methods are static, the constructor is left blank.
     *
     * @since 0.0.1
     */
    public function __construct() {}

    /**
     * The destructor of the Encryption class.
     *
     * As the main encryption methods are static, the destructor is left blank.
     *
     * @since 0.0.1
     */
    public function __destruct() {}

    /* Methods */
    /**
     * Salt, peppers and hashes a plaintext password using SHA-256 hashing algorithm.
     *
     * Before the plaintext password is hashed, a salt is added to the prefix and a pepper is
     * added to the suffix. This prevents the usage of a rainbow table, however, it does not entirely
     * prevent brute-forcing.
     *
     * @since 0.0.1
     *
     * @param string $salt Used to salt the beginning of the plaintext password.
     * @param string $password The plain text password that needs to be salt, peppered and hashed.
     * @param string $pepper Used to pepper the ending of the plaintext password.
     * @return string The hashed version of the salt and peppered plaintext password.
     */
    public static function hashPassword($salt, $password, $pepper)
    {
        // Salt and pepper the password before hasing.
        $spPassword = $salt . $password . $pepper;

        // SHA-256 hash the salt and peppered password and then return it.
        return hash("sha256", $spPassword);
    }
}
