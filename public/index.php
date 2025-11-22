<?php

$defaultController = 'auth';
$defaultAction     = 'loginForm';

$controllerName = $_GET['controller'] ?? $defaultController;
$actionName     = $_GET['action']     ?? $defaultAction;

$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile  = __DIR__ . '/../controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "Controlador no encontrado: " . htmlspecialchars($controllerClass);
    exit();
}

require_once $controllerFile;

if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo "Clase de controlador no válida: " . htmlspecialchars($controllerClass);
    exit();
}

$controller = new $controllerClass();

if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    echo "Acción no encontrada: " . htmlspecialchars($actionName);
    exit();
}

$controller->$actionName();
