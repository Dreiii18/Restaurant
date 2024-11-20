<?php
session_start();
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$isLoggedIn = isset($_SESSION['user']);
if ($isLoggedIn) {
    $userId = $_SESSION['user']['userid'];
    $userRole = $core->getTableColumns('role', 'user', "userid = '{$userId}'")[0]['role'];

    if ($userRole !== 'Customer') {
        $userData = [];
    } else {
        $userData = $core->getReservationDetails($userId);
    }
} else {
    $userData = [];
}

echo json_encode($userData);