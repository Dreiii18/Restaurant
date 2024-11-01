<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$orders = $_REQUEST['orders'];

// $core->generateOrder();
$core->addOrder($orders);