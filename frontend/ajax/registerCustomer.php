<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$customerDetails = $_REQUEST['customerDetails'];

// if ($core->registerCustomer((array)$customerDetails)) {
//     echo json_encode(['success' => true]);
// } else {
//     echo json_encode(['success' => false]);
// }

function displayModal($title, $content, $type = 'success') {
    $headerClass = $type === 'success' ? 'text-success' : 'text-danger';
    return "
    <div class='modal fade' id='staticBackdrop' data-bs-backdrop='static' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
                <div class='modal-header {$headerClass}'>
                    <h5 class='modal-title' id='staticBackdropLabel'>{$title}</h5>
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

$response = $core->registerCustomer((array)$customerDetails);

$html = '';
$msg = '';
$status = '';
if (isset($response['error'])) {
    $errorMessage = "There was an error processing your request. Please try again later.";
    $html = displayModal("Registration Failed", $errorMessage, 'error');
    $msg = $response['msg'];
    $status = false;
} else {
    $successMessage = "Your account has been successfully registered!";
    $html = displayModal("Registration Complete", $successMessage);
    $status = $response;
}

echo json_encode([
    'html' => $html,
    'msg' => $msg,
    'status' => $status,
]);