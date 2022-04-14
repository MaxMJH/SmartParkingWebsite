<?php
namespace app\includes\core;

session_start();

$router->defineRoute('/', 'controller/LoginController.php');
$router->defineRoute('search', 'controller/SearchController.php');
$router->defineRoute('add', 'controller/AddController.php');
$router->defineRoute('error', 'controller/ErrorController.php');
$router->defineRoute('results', 'controller/ResultsController.php');
$router->defineRoute('settings', 'controller/SettingsController.php');
$router->defineRoute('scrapers', 'controller/ScraperController.php');
