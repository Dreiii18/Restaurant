<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$deliveries = $core->getDeliveries();

function displayPendingDeliveries($key, $orderNumber, $address, $itemCount) {
    $itemRow = "
        <div class='accordion-item' id='delivery-{$key}'>
            <h2 class='accordion-header' id='heading-{$key}'>
                <button
                    class='accordion-button collapsed'
                    type='button'
                    data-bs-toggle='collapse'
                    data-bs-target='#collapse-{$key}'
                    aria-expanded='false'
                    aria-controls='collapse-{$key}'
                >
                    Delivery Number: {$orderNumber}
                </button>
            </h2>
            <div class='accordion-collapse collapse' id='collapse-{$key}' aria-labelledby='heading-{$key}' data-bs-parent='pendingDeliveries'>
                <div class='accordion-body'>
                    <p>Address: {$address}</p>
                    <p>Item count: {$itemCount}</p>
                    <input type='button'id='btn-{$key}'class='btn btn-primary' value='Accept'></input>
                </div>
            </div>
        </div>
    ";

    return $itemRow;
}

$html = "";
foreach($deliveries as $key => $value) {
    $html .= displayPendingDeliveries($key + 1, $value['deliveryNumber'], $value['address'], $value['itemCount']);
}


echo json_encode($html);