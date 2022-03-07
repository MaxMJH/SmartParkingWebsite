<?php
namespace app\includes\test;

use PHPUnit\Framework\TestCase;
use app\includes\core\Encryption;

class TestEncryption extends TestCase {
	public function testEncryption() {
		$salt = 'rAndOmwOrd';
		$password = 'vErybAdpAsswOrd';
		$pepper = 'drOwmOdnAr';

		$hashedPassword = Encryption::hashPassword($salt, $password, $pepper);

		$this->assertSame($hashedPassword, '14725aa27e0d7e618a0b91f94de5dec8ebaefe5f39d776f4ef54ec0a598a54b0');
	}

	public function testEncryptionIncorrectHash() {
		$salt = 'rAndOmwOrd';
                $password = 'vErybAdpAsswOrd';
                $pepper = 'drOwmOdnAr';

                $hashedPassword = Encryption::hashPassword($pepper, $password, $salt);

                $this->assertFalse($hashedPassword == '14725aa27e0d7e618a0b91f94de5dec8ebaefe5f39d776f4ef54ec0a598a54b0');
	}
}
