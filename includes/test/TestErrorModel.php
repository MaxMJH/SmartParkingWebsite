<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\model\ErrorModel;

class TestErrorModel extends TestCase
{
    public function testErrorModelInstance()
    {
        $errorModel = null;

        $errorModel = new ErrorModel;

        $this->assertNotNull($errorModel);
    }

    public function testAddErrorMessage()
    {
        $errorModel = new ErrorModel;

        $errorModel->addErrorMessage('Test error');

        $this->assertTrue($errorModel->hasErrors());
    }

    public function testHasErrorsWithoutErrors()
    {
        $errorModel = new ErrorModel;

        $this->assertFalse($errorModel->hasErrors());
    }

    public function testMultipleAddErrorMessage()
    {
        $errorModel = new ErrorModel;

        $errorModel->addErrorMessage('Test error.');
        $errorModel->addErrorMessage('Another error.');

        $this->assertSame($errorModel->getErrorMessage(), 'Test error. Another error. ');
    }
}
