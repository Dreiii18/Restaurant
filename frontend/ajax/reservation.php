<?php
session_start();
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$reservation = $_REQUEST['reservation'];

$userId = isset($_SESSION['user']) ? $_SESSION['user']['userid'] : "";

$confirmedReservation = $core->addReservation(array($reservation), $userId);

$reservationNumber = $confirmedReservation['reservation_number'];
$tableNumber = $confirmedReservation['table_number'];
$reservationDateTime = $confirmedReservation['reservation_datetime'];
$reservationEndDateTime = $confirmedReservation['reservation_end_datetime'];

function getReservationDetails($reservationNumber, $tableNumber, $reservationDateTime, $reservationEndDateTime) {
    return "
        <p><strong>Reservation Number:</strong> $reservationNumber</p>
        <p><strong>Table Number:</strong> $tableNumber</p>
        <p>
            <strong>Date and Time:</strong><br>
            &Tab;Start: $reservationDateTime<br>
            &Tab;End: $reservationEndDateTime
        </p>
    ";
}

?>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Reservation Confirmed!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Your reservation has been successfully placed. Here are the details: <br><br>
                <span>Reservation Number:</span><?php echo $reservationNumber?>  <br>
                <?php echo getReservationDetails($reservationNumber, $tableNumber, $reservationDateTime, $reservationEndDateTime); ?> <br><br>
                Thank you for choosing our restaurant. We look forward to serving you!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Understood</button> -->
            </div>
        </div>
    </div>
</div>