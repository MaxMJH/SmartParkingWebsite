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

    public function testValidateName() {
        $validate = new Validate;

        $name = 'John Smith';
        $this->assertSame($validate->validateName($name), $name);
    }

    public function testValidateNameLengthViolation() {
        $validate = new Validate;

        $name = 'Jo';
        $this->assertSame($validate->validateName($name), false);
    }

    public function testValidateNameCharacterViolation() {
        $validate = new Validate;

        $name = 'Jo<>hn Smith';
        $this->assertSame($validate->validateName($name), 'John Smith');
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

        $city = 'Notti|ngham';
        $this->assertSame($validate->validateCity($city), false);
    }

    public function testValidateCityID()
    {
        $validate = new Validate;

        $cityID = 1;
        $this->assertSame($validate->validateCityID($cityID), $cityID);
    }

    public function testValidateCityIDWithWord()
    {
        $validate = new Validate;

        $cityID = "test";
        $this->assertSame($validate->validateCityID($cityID), false);
    }

    public function testValidateXMLURL()
    {
        $validate = new Validate;

        $xmlURL = 'https://geoserver.nottinghamcity.gov.uk/parking/defstatus.xml';
        $this->assertSame($validate->validateXMLURL($xmlURL), $xmlURL);
    }

    public function testValidateXMLURLWithIncorrectURL()
    {
        $validate = new Validate;

        $xmlURL = 'https://geoserver.nottinghamcity.gov.uk/park|ing/defstatus.xml';
        $this->assertSame($validate->validateXMLURL($xmlURL), false);
    }

    public function testValidateElements()
    {
        $validate = new Validate;

        $elements = 'Test';
        $this->assertSame($validate->validateElements($elements), $elements);
    }

    public function testValidateElementsWithCharacterViolation()
    {
        $validate = new Validate;

        $elements = 'Test|';
        $this->assertSame($validate->validateElements($elements), false);
    }

    public function testValidateElementsWithLengthViolation()
    {
        $validate = new Validate;

        $elements = 'T';
        $this->assertSame($validate->validateElements($elements), false);
    }

    public function testValidateID()
    {
        $validate = new Validate;

        $id = '1';
        $this->assertSame($validate->validateID($id), '1');
    }

    public function testValidateIDWithWord()
    {
        $validate = new Validate;

        $id = "word";
        $this->assertSame($validate->validateID($id), false);
    }
}
