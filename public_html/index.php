<?php
session_start();
require_once __DIR__ . "/../vendor/autoload.php";
define("TEMPLATE_DIR", realpath(__DIR__ . '/../app/views'));

$appDir = realpath(__DIR__ . '/../app');
new \App\Core\BootLoader();

$routes = explode('/', $_SERVER['REQUEST_URI']);
$controller_name = "Main";
$action_name = 'index';

if (!empty($routes[1])) {
    $controller_name = $routes[1];
    $exploaded = explode('?', $controller_name);
    $controller_name = $exploaded[0];
}

if (!empty($routes[2])) {
    $action_name = $routes[2];
    $exploaded = explode('?', $action_name);
    $action_name = $exploaded[0];
}

$filename = ($appDir . DIRECTORY_SEPARATOR . "controllers/" . strtolower($controller_name) . ".php");
try {
    if (file_exists($filename)) {
        require_once $filename;
    } else {
        throw new Exception("File not found");
    }
    $classname = '\App\Controllers\\' . ucfirst($controller_name);
    if (class_exists($classname)) {
        $controller = new $classname();
    } else {
        throw new Exception("File found but class not found");
    }
    if (method_exists($controller, $action_name)) {
        $controller->$action_name();
    } else {
        throw new Exception("Method not found");
    }
} catch (Exception $e) {
    require($appDir . DIRECTORY_SEPARATOR . "errors/404.php");
}

