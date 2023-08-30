<?php

require_once(__DIR__ . '/config.php');

global $CFG;

// Obtener la URL actual
$url = explode('?', $_SERVER['REQUEST_URI'])[0];

// Definir las rutas
$routes = [
    $CFG->wwwroot . '/' => ['controller' => 'MainController.php', 'name' => 'Inicio', 'url' =>  $CFG->wwwroot . '/',  'active' => $CFG->wwwroot . '/' == $url],
];

$CFG->routes = $routes;

if (array_key_exists($url, $routes)) {
    $_SESSION['url'] = $url;
    require_once(__DIR__ . '/app/controllers/' . $routes[$url]['controller']);
} else if (strpos($url, '/api/') !== false){
    require_once(__DIR__ . '/app/controllers/APIController.php');
}