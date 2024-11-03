<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$reservation = $_REQUEST['reservation'];

$order = $core->addReservation(array($reservation));
?>
<h1>

</h1>