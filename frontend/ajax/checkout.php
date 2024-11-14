<?php
session_start();
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$transaction = $_REQUEST['transaction'];

$userId = isset($_SESSION['user']) ? $_SESSION['user']['userid'] : "";

// Modify your return to output a proper response
if ($core->addTransaction((array)$transaction, $userId)) {
    echo json_encode(['success' => true]); 
} else {
    echo json_encode(['success' => false]); 
}