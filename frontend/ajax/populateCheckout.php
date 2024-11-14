<?php
session_start();
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$isLoggedIn = isset($_SESSION['user']);
$userData = $isLoggedIn ? $core->getTransactionDetails($_SESSION['user']['userid']) : [];
// print_r($userData);
echo json_encode($userData);