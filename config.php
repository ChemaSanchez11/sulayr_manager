<?php

require_once(__DIR__ . '/db/DB.php');
use db\DB;

// Incluir la clase Dotenv
require_once __DIR__ . '/lib/Dotenv.php';

session_start();

// Crear una instancia de Dotenv y cargar las variables de entorno
$dotenv = new Dotenv(__DIR__ . '/.env');
$dotenv->load();

// Ahora puedes usar las variables de entorno
$db_host = getenv('DB_HOST');
$db_port = getenv('DB_PORT');
$db_user = getenv('DB_USER');
$db_password = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');

unset($CFG, $DB);

global $CFG, $DB;

$CFG = new stdClass();
$CFG->shortname = 'leobus';
$CFG->wwwroot = '/sulayr_manager';

$DB = new DB($db_host, $db_user, $db_password, $db_port, $db_name);