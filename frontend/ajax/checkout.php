<?php
session_start();
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$transaction = $_REQUEST['transaction'];

$userId = isset($_SESSION['user']) ? $_SESSION['user']['userid'] : "";

$response = $core->addTransaction((array)$transaction, $userId);

function displayOrderItems($items) {
    $output = '';
    foreach ($items as $item) {
        $output .= "
            <div class='item row justify-content-between border-bottom'>
                <p class='col text-start overflow-auto'>{$item['menuName']}</p>
                <p class='col text-center'>{$item['quantity']}</p>
                <p class='col text-center'>{$item['menuPrice']}</p>
                <p class='col text-end'>{$item['totalPrice']}</p>
            </div>
        ";
    }
    return $output;
}

function displayModal($title, $content, $type = 'success') {
    $headerClass = $type === 'success' ? 'text-success' : 'text-danger';
    return "
    <div class='modal fade' id='orderModal' data-bs-backdrop='static' tabindex='-1' aria-labelledby='orderModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
                <div class='modal-header {$headerClass}'>
                    <h5 class='modal-title' id='orderModalLabel'>{$title}</h5>
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
    $itemsHtml = displayOrderItems($response['items']);
    $deliveryHtml = $response['delivery'] 
    ? "<p class='border-bottom'><strong>Delivery Address:</strong></p>
    <p class='border-bottom'>{$response['delivery']['house_number']} {$response['delivery']['street_number']} {$response['delivery']['street_name']}, {$response['delivery']['postal_code']}</p>"
    : "";

    $content = "
        <p><strong>Order Number:</strong> {$response['orderNumber']}</p>
        <p><strong>Transaction Number:</strong> {$response['transactionDetails']['transaction_number']}</p>
        <p><strong>Date/Time:</strong> {$response['transactionDetails']['transaction_datetime']}</p>
        <p><strong>Contact Number:</strong> {$response['phoneNumber']}</p>
        <p><strong>Order Type:</strong> {$response['orderType']}</p>
        <p><strong>Payment Method:</strong> {$response['paymentType']}</p>
        <p>Items Purchased:</p>
        <div class='row justify-content-between border-top border-bottom'>
            <p class='col text-start'>Menu Name</p>
            <p class='col text-center'>Quantiy</p>
            <p class='col text-center'>Price</p>
            <p class='col text-end'>Total</p>
        </div>
        {$itemsHtml}
        <p><strong>Subtotal:</strong> {$response['transactionDetails']['sub_total']}</p>
        <p><strong>Tax:</strong> {$response['transactionDetails']['tax']}</p>
        <p><strong>Tip:</strong> {$response['transactionDetails']['tip']}</p>
        <p><strong>Grand Total:</strong> {$response['transactionDetails']['total']}</p>
        {$deliveryHtml}
        <p>Thank you for your order!</p>";

    $html = displayModal("Order Receipt", $content);
}

echo json_encode([
    'html' => $html,
    'msg' => $msg
]);