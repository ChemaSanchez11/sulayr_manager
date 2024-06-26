<?php

use db\DB;

require_once(__DIR__ . '/db/DB.php');

session_start();

unset($CFG, $DB);

global $CFG, $DB;

$CFG = new stdClass();
$CFG->shortname = 'leobus';
$CFG->wwwroot = '/sulayr_manager';

$DB = new DB('localhost', 'root', '', '3306', 'sulayr_manager');