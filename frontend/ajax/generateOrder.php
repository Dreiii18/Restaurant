<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

if (isset($_REQUEST['orders'])) {
    $orderList = $_REQUEST['orders'];
}

function displayErrorMessage($msg) {
    $itemRow = "
    <div class='modal fade' id='staticBackdrop' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header text-danger'>
                    <h1 class='modal-title fs-5' id='staticBackdropLabel'>Order Failed!</h1>
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

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['userid'];
    if ($_SESSION['user']['role'] === 'Customer') {
        $response = $core->addOrder($orderList, null);
    }

    if ($_SESSION['user']['role'] === 'Employee') {
        $response = $core->addOrder($orderList, (int)$_SESSION['user']['roleid']);
    }
} else {
    $response = $core->addOrder($orderList, null);
}

$html = '';
$status = true;
$msg = '';
$value = '';
if (isset($response['error'])) {
    $html = displayErrorMessage('There was en error processing your order. Please try again later.');
    $msg = $response['msg'];
    $status = false;
} else {
    $value = $response;
}
echo json_encode([
    'html' => $html,
    'status' =>$status ,
    'msg' => $msg,
    'value' => $value,
]);
exit;