<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\model\LoginModel;

class TestLoginModel extends TestCase
{
    public function testLoginModelInstance()
    {
        $loginModel = null;

        $loginModel = new LoginModel;

        $this->assertNotNull($loginModel);
    }

    public function testSaltAndPepperPassword()
    {
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('johnsmithjds@gmail.com');
        $loginModel->setPassword('password123');

        $loginModel->saltAndPepperPassword();

        $this->assertSame($loginModel->getPassword(), 'bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e');
    }

    public function testSaltAndPepperPasswordWithIncorrectEmail()
    {
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('johnsmithjds@gmail.co.uk');
        $loginModel->setPassword('password321');

        $this->assertSame($loginModel->saltAndPepperPassword(), false);
    }

    public function testPopulateUser()
    {
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('johnsmithjds@gmail.com');
        $loginModel->setPassword('bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e');

        $this->assertSame($loginModel->populateUser(), true);
    }

    public function testPopulateUserWithIncorrectEmail()
    {
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('johnsmithjds@gmail.co.uk');
        $loginModel->setPassword('bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e');

        $this->assertSame($loginModel->populateUser(), false);
    }

    public function testPopulateUserWithIncorrectPassword()
    {
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('johnsmithjds@gmail.com');
        $loginModel->setPassword('password');

        $this->assertSame($loginModel->populateUser(), false);
    }
}
