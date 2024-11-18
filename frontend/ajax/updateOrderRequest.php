<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$supplyOrders = $_REQUEST['supplyOrders'];
$status = $_REQUEST['status'];

switch ($status) {
    case "Approve": 
        $status = "Approved";
        break;
    case "Reject":
        $status = "Rejected";
        break;
    default:
        break;
}

print_r($core->updateOrderRequest($supplyOrders, $status));