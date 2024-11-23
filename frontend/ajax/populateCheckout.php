<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$isLoggedIn = isset($_SESSION['user']);
if ($isLoggedIn) {
    if (!$core->isAllowed('order') || !isset($_COOKIE['cartItems'])) {
        echo json_encode("not_found");
        die();
    } 
    $userId = $_SESSION['user']['userid'];
    $userRole = $core->getTableColumns('role', 'user', "userid = '{$userId}'")[0]['role'];

    $userData = $userRole !== 'Customer' ? [] : $core->getTransactionDetails($userId);

} else {
    echo json_encode("not_found");
    die();
}
echo json_encode($userData);