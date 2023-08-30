<?php

require_once(__DIR__ . '/../lib/mustache/src/Mustache/Autoloader.php');

//Renderizar templates
function render_template($template, $data)
{
    Mustache_Autoloader::register();
    $mustache = new Mustache_Engine();

    $template = file_get_contents(__DIR__ . "/../templates/$template.mustache");

    echo $mustache->render($template, $data);
}

function get_proyects() {
    global $DB;

    $sql = 'SELECT
                p.*,
                ( SELECT total FROM visit v WHERE v.id_proyect = p.id ) as total,
                (SELECT SUM(i.count) FROM visit v INNER JOIN `ip_visit` i ON i.id_visit = v.id WHERE v.id_proyect = p.id AND i.count > 1) as repeats,
                (SELECT COUNT(i.id) FROM visit v INNER JOIN `ip_visit` i ON i.id_visit = v.id WHERE v.id_proyect = p.id AND i.count = 1) as no_repeats
            FROM
                proyects p 
            WHERE
                p.active = 1';

    return $DB->get_records($sql);
}