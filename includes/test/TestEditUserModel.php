<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\model\EditUserModel;
use app\includes\model\LoginModel;

class TestEditUserModel extends TestCase
{
    public function testEditUserModelInstance()
    {
        $editUserModel = null;

        $editUserModel = new EditUserModel;

        $this->assertNotNull($editUserModel);
    }

    public function testUpdateUser()
    {
        $editUserModel = new EditUserModel;
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('johnsmithjds@gmail.com');
        $loginModel->setFirstName('John');
        $loginModel->setLastName('Test');
        $loginModel->setPassword('bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e');
        $loginModel->setIsAdmin(true);
        $loginModel->setUserID(2);

        $editUserModel->setLoginModel($loginModel);

        $this->assertSame($editUserModel->updateUser(), true);
    }

    public function testUpdateUserOnNonUser()
    {
        $editUserModel = new EditUserModel;
        $loginModel = new LoginModel;

        $loginModel->setEmailAddress('nonuser@gmail.com');
        $loginModel->setFirstName('Non');
        $loginModel->setLastName('User');
        $loginModel->setPassword('bc2049dd5775b5dd4306f1ffebfad10d140a5fefd5acec7ae5278e57629f372e');
        $loginModel->setIsAdmin(false);
        $loginModel->setUserID(5);

        $editUserModel->setLoginModel($loginModel);

        $this->assertSame($editUserModel->updateUser(), false);
    }

    public function testEmailAddressExists()
    {
        $editUserModel = new EditUserModel;

        $this->assertSame($editUserModel->emailAddressExists('johnsmithjds@gmail.com'), true);
    }

    public function testEmailAddressExistsOnNonUser()
    {
        $editUserModel = new EditUserModel;

        $this->assertSame($editUserModel->emailAddressExists('joansmithjds@gmail.com'), false);
    }
}
