<?php
require 'core/Router.php';
require 'core/Request.php';

$uri = trim($_SERVER['REQUEST_URI'], '/');

echo Router::load('core/Routes.php')->direct(Request::uri(), Request::method());
