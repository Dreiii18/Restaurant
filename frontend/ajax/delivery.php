<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

if (isset($_SESSION['user'])) {
    if (!$core->isAllowed('delivery')) {
        echo json_encode("not_found");
        die();
    } 
} else {
    echo json_encode("not_found");
    die();
}

$deliveries = $core->getDeliveries($_SESSION['user']['userid']);

function displayDeliveries($key, $orderNumber, $address, $itemCount, $status) {
    $accordionId = strtolower(str_replace(' ', '', $status)) . "Deliveries";
    $btnValue = $status === 'Pending' ? 'Accept' : 'In Transit';
    $btnClass = $status === 'Pending' ? 'btn-primary' : 'btn-outline-warning';
    $btnDisabled = $status === 'In Transit' ? 'disabled' : '';
    $collapseId = "collapse-{$accordionId}-body-{$key}";

    $itemRow = "
        <div class='accordion-item' id='{$accordionId}-{$key}'>
            <h2 class='accordion-header' id='heading-{$key}'>
                <button
                    class='accordion-button collapsed'
                    type='button'
                    data-bs-toggle='collapse'
                    data-bs-target='#{$collapseId}'
                    aria-expanded='false'
                    aria-controls='{$collapseId}'
                >
                    Delivery Number: {$orderNumber}
                </button>
            </h2>
            <div class='accordion-collapse collapse' id='{$collapseId}' aria-labelledby='heading-{$key}' data-bs-parent='{$accordionId}'>
                <div class='accordion-body'>
                    <p>Address: {$address}</p>
                    <p>Item count: {$itemCount}</p>
                    <input type='button' id='{$accordionId}-btn-{$key}' class='btn {$btnClass}' value='{$btnValue}' {$btnDisabled}></input>";
                    if ($status === 'In Transit') {
    $itemRow .=         "<input type='button' id='btn-delivered-{$key}' class='btn btn-success' value='Delivered'>";
                    }
    $itemRow .="</div>
            </div>     
        </div>
    ";

    return $itemRow;
}

$htmlPending = "";
$htmlInTransit = "";

foreach($deliveries['pending'] as $key => $value) {
    $htmlPending .= displayDeliveries($value['deliveryNumber'], $value['deliveryNumber'], $value['address'], $value['itemCount'], 'Pending');
}

foreach($deliveries['in_transit'] as $key => $value) {
    $htmlInTransit .= displayDeliveries($value['deliveryNumber'], $value['deliveryNumber'], $value['address'], $value['itemCount'], 'In Transit');
}


echo json_encode([
    'pending' => $htmlPending,
    'in_transit' => $htmlInTransit
]);