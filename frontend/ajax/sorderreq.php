<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

if (isset($_SESSION['user'])) {
    if (!$core->isAllowed('supply_order')) {
        echo json_encode("not_found");
        die();
    } 
} else {
    echo json_encode("not_found");
    die();
}

$orderRequests = $core->getOrderRequests();

function displayOrderItems($itemName, $supplierName, $costPerUnit, $quantityOrdered, $totalCost) {
    $itemRow = "
        <div class='item row justify-content-between'>
            <span class='col text-start'>{$itemName}</span>
            <span class='col text-center'>{$quantityOrdered}</span>
            <span class='col text-center'>{$costPerUnit}</span>
            <span class='col text-center'>{$supplierName}</span>
            <span class='col text-end'>{$totalCost}</span>
        </div>
    ";
    return $itemRow;
}

function displayNewRow($key, $orderDateTime, $items, $totalCost) {
    $itemRow = "
        <div class='order' id='order-{$key}'>
            <button class='collapsible d-flex justify-content-between align-items-center'>
                <span>Order {$key}</span>
                <span class='text-end flex-grow-1'>
                Date Ordered: {$orderDateTime}</span>
            </button>
            <div class='order-content'>
                <div class='item-titles row justify-content-between'>
                    <span class='col-title col text-start'>Item Name</span>
                    <span class='col-title col text-center'>Quantity</span>
                    <span class='col-title col text-center'>Cost Per Unit</span>
                    <span class='col-title col text-center'>Supplier</span>
                    <span class='col-title col text-end'>Cost</span>
                </div>

                <div class='order-items'>";
                    foreach ($items as $item) {
                        $itemName = $item['itemName'];
                        $supplierName = $item['supplierName'];
                        $costPerUnit = $item['costPerUnit'];
                        $quantityOrdered = $item['quantityOrdered'];
                        $totalItemCost = $item['totalCost'];
                        $itemRow .= displayOrderItems($itemName, $supplierName, $costPerUnit, $quantityOrdered, $totalItemCost);    
                    }
    $itemRow .= "</div>

                <div class='row align-items-center p-2'>
                    <div class='col-6 d-flex justify-content-start'>
                        <span></span>
                    </div>
                    <div class='col-6 d-flex justify-content-end'>
                        <span>Total Cost: \${$totalCost}</span>
                    </div>
                </div> 
                <div class='order-buttons d-flex justify-content-end'>
                    <button class='reject' id='btn-reject-{$key}'>Reject</button>
                    <button class='approve' id='btn-approve-{$key}'>Approve</button>
                </div>
            </div>
        </div>
    ";

    return $itemRow;
}

function displayNoOrders() {
    $noOrders = "
        <div class='modal fade' id='noOrders' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel1' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h1 class='modal-title fs-5' id='staticBackdropLabel1'>No Order Requests</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                        There are currently no pending order requests. 
                    </div>
                    <div class='modal-footer'>
                        <button type='button' id='close' class='btn btn-primary' data-bs-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div>
    ";

    return $noOrders;
}

$supplyOrders = [];
$html = "";
$status = "";

if (count($orderRequests) > 0) {
    foreach ($orderRequests as $order) {
        $orderId = $order['supplyOrderId'];
        $orderDateTime = $order['supplyOrderDateTime'];
        $items = $order['items'];
        $totalCost = number_format($order['totalCost'], 2);
    
        $html .= displayNewRow($orderId, $orderDateTime, $items, $totalCost);
        $status = "success";
    }
} else {
    $html = displayNoOrders();
    $status = "fail";
}

echo json_encode([
    'html' => $html,
    'status' => $status
]);