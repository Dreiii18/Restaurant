<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$orderList = $_REQUEST['orders'];

// $core->generateOrder();
$order = $core->addOrder($orderList);
?>
<h1>
    <?php echo $order ?>
</h1>