<?php

global $CFG;

require_once(__DIR__ . '/../../functions/functions.php');


$total_connections = 0;

$proyects = [];
foreach (get_proyects() as $proyect){

    $data = json_decode($proyect->data);

    $proyects[] = [
        'id' => $proyect->id,
        'shortname' => $proyect->shortname,
        'name' => $proyect->name,
        'domain' => $proyect->domain,
        'active' => $proyect->id,
        'total' => $proyect->total ?? 0,
        'repeats' => $proyect->repeats ?? 0,
        'no_repeats' => $proyect->no_repeats ?? 0,
        'database' => $data->database,
        'ssl_certificate' => $data->ssl_certificate,
        'centro_red_domain' => $data->centro_red_domain,
        'cache_data' => $data->cache_data
    ];

    $total_connections += (int) $proyect->total;
}

render_template('index', [
    'url' => $CFG->wwwroot,
    'urls' => array_values($CFG->routes),
    'total_proyects' => count($proyects),
    'total_connections' => $total_connections,
    'proyects' => $proyects
]);