<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$customerDetails = $_REQUEST['customerDetails'];

if ($core->registerCustomer((array)$customerDetails)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}