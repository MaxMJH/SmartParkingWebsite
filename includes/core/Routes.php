<?php
$router->get('', 'controller/LoginController.php');
$router->get('index', 'controller/LoginController.php');
$router->post('search', 'controller/SearchController.php');
$router->get('error', 'controller/ErrorController.php');
$router->post('results', 'controller/ResultsController.php');

