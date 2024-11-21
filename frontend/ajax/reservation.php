<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

sleep(3);
$reservation = $_REQUEST['reservation'];

$userId = isset($_SESSION['user']) ? $_SESSION['user']['userid'] : "";

$confirmedReservation = $core->addReservation(array($reservation), $userId);

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

function displaySuccessMessage($reservationNumber, $tableNumber, $reservationDateTime, $reservationEndDateTime) {
    $itemRow = "
    <div class='modal fade' id='staticBackdrop' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header text-success'>
                    <h1 class='modal-title fs-5' id='staticBackdropLabel'>Reservation Confirmed!</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    Your reservation has been successfully placed. Here are the details: <br><br>
                    <span>Reservation Number:</span>{$reservationNumber} <br>";
    $itemRow .= getReservationDetails($reservationNumber, $tableNumber, $reservationDateTime, $reservationEndDateTime); 
    $itemRow .= "<br><br> Thank you for choosing our restaurant. We look forward to serving you!
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>
    </div>";

    return $itemRow;
}

function displayErrorMessage($msg) {
    $itemRow = "
    <div class='modal fade' id='staticBackdrop' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header text-danger'>
                    <h1 class='modal-title fs-5' id='staticBackdropLabel'>Reservation Failed!</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    {$msg} <br><br>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>
    </div>";

    return $itemRow;
}

$html = "";
$msg = "";


if (isset($confirmedReservation['error'])) {
    // Reservation failed, display error message
    if ($confirmedReservation['error'] === 'no_available_tables') {
        $html = displayErrorMessage('There are no available tables for the selected date and time. Please select a different time.');
    }

    if ($confirmedReservation['error'] === 'insertion_error') {
        $html = displayErrorMessage('There was en error processing your reservation. Please try again later.');
    }

    $msg = $confirmedReservation['msg'];
} else {
    // Reservation successful, display success message
    $reservationNumber = $confirmedReservation['reservation_number'];
    $tableNumber = $confirmedReservation['table_number'];
    $reservationDateTime = $confirmedReservation['reservation_datetime'];
    $reservationEndDateTime = $confirmedReservation['reservation_end_datetime'];
    $html = displaySuccessMessage($reservationNumber, $tableNumber, $reservationDateTime, $reservationEndDateTime);
}

echo json_encode([
    'html' => $html,
    'msg' => $msg
]);