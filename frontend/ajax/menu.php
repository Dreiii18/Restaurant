<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

sleep(3);
$menu = $core->getMenuList();

function displayItem($item) {
    $item = "
        <div>
            <div class='row justify-content-between'>
                <span class='col text-start'>{$item['menu_item_name']}</span>
                <span class='col text-end'>{$item['menu_price']}</span>
            </div>
            <div class='row'>
                <span class='col'>{$item['menu_description']}</span>
            </div><br>
        </div>
    ";

    return $item;
}

function displayFullMenu($menu) {
    $fullMenu = "
    <div class='modal fade' id='menu' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='menu-list' aria-hidden='true'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h2 class='modal-title fs-5' id='menu-list'>Menu</h2>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>";
                    foreach ($menu as $menuItem) {
    $fullMenu .=        displayItem($menuItem);
                    }
    $fullMenu .=    "
                </div>
                <div class='modal-footer'>
                    <button type='button' id='close' class='btn btn-primary' data-bs-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>
    </div>
";

return $fullMenu;
}

$html = displayFullMenu($menu);
echo json_encode([
    'html' => $html
]);