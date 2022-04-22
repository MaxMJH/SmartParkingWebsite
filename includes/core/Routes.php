<?php
namespace app\includes\core;

// As this is the first script called, it is important to start the session here.
session_start();

// Define all needed routes for the site to function.
$router->defineRoute('/', 'controller/LoginController.php');
$router->defineRoute('search', 'controller/SearchController.php');
$router->defineRoute('add', 'controller/AddController.php');
$router->defineRoute('remove', 'controller/RemoveController.php');
$router->defineRoute('error', 'controller/ErrorController.php');
$router->defineRoute('results', 'controller/ResultsController.php');
$router->defineRoute('settings', 'controller/SettingsController.php');
$router->defineRoute('scrapers', 'controller/ScraperController.php');
$router->defineRoute('users', 'controller/UserController.php');
$router->defineRoute('users-edit', 'controller/EditUserController.php');
$router->defineRoute('reviews', 'controller/ReviewController.php');
