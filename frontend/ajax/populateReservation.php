<?php
session_start();
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$isLoggedIn = isset($_SESSION['user']);
$userData = $isLoggedIn ? $core->getReservationDetails($_SESSION['user']['userid']) : [];

echo json_encode($userData);