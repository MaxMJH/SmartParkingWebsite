<?php
require 'core/Router.php';
require 'core/Request.php';

echo Router::loadRoutes('Routes.php')->redirectToRoute(Request::uri(), Request::method());
