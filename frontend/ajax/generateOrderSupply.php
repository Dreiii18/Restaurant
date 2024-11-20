<?php
session_start();
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$orders = $_REQUEST['orders'];

$userId = isset($_SESSION['user']) ? $_SESSION['user']['userid'] : "";

$response = $core->addOrderSupply($orders, $userId);

function displayOrderItems($orders) {
    $output = '';
    foreach ($orders as $item) {
        $output .= "
            <div class='item row justify-content-between border-bottom'>
                <p class='col text-start overflow-auto'>{$item['itemName']}</p>
                <p class='col text-center'>{$item['unitPrice']}</p>
                <p class='col text-center'>{$item['quantity']}</p>
                <p class='col text-center overflow-auto'>{$item['supplier']}</p>
                <p class='col text-end'>{$item['totalCost']}</p>
            </div>
        ";
    }
    return $output;
}

function displayModal($title, $content, $type = 'success') {
    $headerClass = $type === 'success' ? 'text-success' : 'text-danger';
    return "
    <div class='modal fade' id='supplyOrderModal' data-bs-backdrop='static' tabindex='-1' aria-labelledby='supplyOrderModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
                <div class='modal-header {$headerClass}'>
                    <h5 class='modal-title' id='supplyOrderModalLabel'>{$title}</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>{$content}</div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>
    </div>";
}

$html = '';
$msg = '';
// Modify your return to output a proper response
if (isset($response['error'])) {
    $errorMessage = "There was an error processing your order. Please try again later.";
    $html = displayModal("Order Failed", $errorMessage, 'error');
    $msg = $response['msg'];
} else {
    $itemsHtml = displayOrderItems($response['orders']);

    $content = "
        <p><strong>Order Number:</strong> {$response['supplyOrderDetails']['supply_orderid']}</p>
        <p><strong>Date/Time:</strong> {$response['supplyOrderDetails']['supply_order_datetime']}</p>
        <p><strong>Employee Name:</strong> {$response['employeeDetails']['employeeName']}</p>
        <p><strong>Employee Email:</strong> {$response['employeeDetails']['employeeEmail']}</p>
        <p><strong>Employee Phone Number:</strong> {$response['employeeDetails']['employeePhoneNumber']}</p>
        <p>Items Ordered:</p>
        <div class='row justify-content-between border-top border-bottom'>
            <p class='col text-start'>Item Name</p>
            <p class='col text-center'>Unit Price</p>
            <p class='col text-center'>Quantiy</p>
            <p class='col text-center'>Supplier</p>
            <p class='col text-end'>Total</p>
        </div>
        {$itemsHtml}
        <p class='col text-end'><strong>Grand Total: </strong>" . number_format($response['total'], 2) . "</p>

        <p><strong>Order Status:</strong> Waiting for Approval</p>";

    $html = displayModal("Order Receipt", $content);
}

echo json_encode([
    'html' => $html,
    'msg' => $msg
]);