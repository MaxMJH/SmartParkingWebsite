<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\model\SettingsModel;
use app\includes\model\LoginModel;

class TestSettingsModel extends TestCase
{
    public function testSettingsModelInstance()
    {
        $settingsModel = null;

        $settingsModel = new SettingsModel;

        $this->assertNotNull($settingsModel);
    }

    public function testUpdateUser()
    {
        $settingsModel = new SettingsModel;
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('johnsmithjds@gmail.com');
        $loginModel->setFirstName('John');
        $loginModel->setLastName('Test');
        $loginModel->setPassword('bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e');
        $loginModel->setIsAdmin(true);
        $loginModel->setUserID(2);

        $this->assertSame($settingsModel->updateUser($loginModel), true);
    }

    public function testUpdateUserOnNonUser()
    {
        $settingsModel = new SettingsModel;
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('nonuser@gmail.com');
        $loginModel->setFirstName('Non');
        $loginModel->setLastName('User');
        $loginModel->setPassword('bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e');
        $loginModel->setIsAdmin(false);
        $loginModel->setUserID(5);

        $this->assertSame($settingsModel->updateUser($loginModel), false);
    }

    public function testEmailAddressExists()
    {
        $settingsModel = new SettingsModel;

        $this->assertSame($settingsModel->emailAddressExists('johnsmithjds@gmail.com'), true);
    }

    public function testEmailAddressExistsOnNonUser()
    {
        $settingsModel = new SettingsModel;

        $this->assertSame($settingsModel->emailAddressExists('joansmithjds@gmail.com'), false);
    }
}
