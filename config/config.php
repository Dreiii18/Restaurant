<?php

// Database configuration variables
$config = [];
$config['db_host'] = 'localhost';
$config['db_user'] = 'root';
$config['db_pass'] = '';
$config['db_name'] = 'restaurant';

$config['path'] = dirname(__FILE__) . '/..';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Libraries required by all scripts
require_once($config['path'] . '/backend/database.php');