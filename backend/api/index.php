<?php
require_once __DIR__ . '/../app/Core/Container.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Config/routes.php';


function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
}

cors();
// Initialize container
$container = new Container();

// Initialize router with container
$router = new Router($container);

// Add routes
$routes = require __DIR__ . '/../app/Config/routes.php';
foreach ($routes as $route => $controllerAction) {
    list($method, $path) = explode(' ', $route);
    $router->add($method, $path, $controllerAction);
}

// Run the router
$router->run();
?>