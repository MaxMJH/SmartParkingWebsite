<?php
$url = dirname(__DIR__, 1) . '/vendor/autoload.php';

require_once $url;

use app\includes\core\Router;
use app\includes\core\Request;

echo Router::loadRoutes('Routes.php')->redirectToRoute(Request::uri());
