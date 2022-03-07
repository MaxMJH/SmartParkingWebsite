<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\core\Validate;

class TestValidate extends TestCase {
    public function testValidateUsername() {
        $validate = new Validate;

        $username = 'johnsmithjds@gmail.com';
        $this->assertSame($validate->validateUsername($username), $username);
    }

    public function testValidateUsernameLengthViolation() {
        $validate = new Validate;

        $username = 'max';
        $this->assertSame($validate->validateUsername($username), false);
    }

    public function testValidateUsernameCharacterViolation() {
        $validate = new Validate;

        $username = 'johnsmithjds@gmail.com<>';
        $this->assertSame($validate->validateUsername($username), 'johnsmithjds@gmail.com');
    }

    public function testValidatePassword() {
        $validate = new Validate;

        $password = 'password';
        $this->assertSame($validate->validatePassword($password), $password);
    }

    public function testValidatePasswordLengthViolation() {
        $validate = new Validate;

        $password = 'pass';
        $this->assertSame($validate->validatePassword($password), false);
    }

    public function testValidatePasswordCharacterViolation() {
        $validate = new Validate;

        $password = 'password<>123';
        $this->assertSame($validate->validatePassword($password), 'password123');
    }

    public function testValidateCity() {
        $validate = new Validate;

        $city = 'Nottingham';
        $this->assertSame($validate->validateCity($city), $city);
    }

    public function testValidateCityLengthViolation() {
        $validate = new Validate;

        $city = 'No';
        $this->assertSame($validate->validateCity($city), false);
    }

    public function testValidateCityCharacterViolation() {
        $validate = new Validate;

        $city = 'Notti<>ngham';
        $this->assertSame($validate->validateCity($city), 'Nottingham');
    }
}
