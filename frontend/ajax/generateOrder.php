<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

if (isset($_REQUEST['orders'])) {
    $orderList = $_REQUEST['orders'];
}

echo json_encode($core->addOrder($orderList));
exit;