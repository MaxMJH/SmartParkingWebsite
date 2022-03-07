<?php
namespace app\includes\core;

class Request {
    public function __construct() {}

    public function __destruct() {}

    public static function uri() {
        return trim($_SERVER['REQUEST_URI'], '/');
    }

    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
}
