<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

if (isset($_SESSION['user'])) {
    if (!$core->isAllowed('order')) {
        echo "not_found";
        die();
    } 
}

function displayMainItem($id) {
    global $core;
    [$menuName, $menuDescription, $menuPrice] = $core->getMenuItem($id);
    $fileName = strtolower(str_replace(' ', '', $menuName));
    $output = "
        <img src='frontend/images/{$fileName}.jpg' class='d-block w-100' alt='{$menuDescription}'>
        <dl>
            <dt class='item_name' data-menu-id='{$id}' data-menu-price='{$menuPrice}'>
                <h2 >{$menuName}</h2>
            </dt>
            <dd>{$menuDescription} \${$menuPrice}</dd>
            <dd>
                <button class='btn add-to-cart' data-menu-id='{$id}' data-menu-name='{$menuName}' data-menu-price='{$menuPrice}'>Add to Cart</button>
            </dd>
        </dl>
    ";
    return $output;
}

function displaySubItems($ids) {
    global $core;
    $output = '';
    foreach($ids as $id) {
        [$menuName, $menuDescription, $menuPrice] = $core->getMenuItem($id);
        $output .= "
            <dt class='item_name' data-menu-id='{$id}' data-menu-name='{$menuName}' data-menu-price='{$menuPrice}'>{$menuName}</dt>
            <dd>{$menuDescription} \${$menuPrice}</dd>
            <dd><button class='btn add-to-cart' data-menu-id='{$id}' data-menu-name='{$menuName}' data-menu-price='{$menuPrice}'>Add to Cart</button></dd>
        ";
    }
    return $output;
}

function displayBestSeller() {
    global $core;
    $bestSeller = $core->getBestSeller();
    $menuId = $bestSeller['menu_itemid'];
    $menuName = $bestSeller['menu_item_name'];
    $fileName = strtolower(str_replace(' ', '', $menuName));
    $menuDescription = $bestSeller['menu_description'];
    $menuPrice = $bestSeller['menu_price'];
    
    $output = "
        <div class='row' id='bestSeller'>
            <h2 class='section-title text-center'>BEST SELLER</h2>
            <div class='row d-flex justify-content-center'  >
                <div class='col text-end'>
                     <dl>
                        <dt class='item_name' data-menu-id='{$menuId}' data-menu-price='{$menuPrice}'>
                            <h2 >{$menuName}</h2>
                        </dt>
                        <dd>{$menuDescription} \${$menuPrice}</dd>
                        <dd>
                            <button class='btn add-to-cart' data-menu-id='{$menuId}' data-menu-name='{$menuName}' data-menu-price='{$menuPrice}'>Add to Cart</button>
                        </dd>
                    </dl>
                </div>
                <div class='col feature-dish'>
                    <img src='frontend/images/{$fileName}.jpg' class='d-block w-100' alt='$menuName'>
                </div>
            </div>
        </div>
    ";

    return $output;
}

function displayMenu() {
    $output = displayBestSeller() . "
        <div class='row' id='starters'>
            <div class='row'>
                <div class='col text-align-center'>
                    <h2 class='section-title'>Starters</h2>
                </div>
            </div>
            <div class='col feature-dish text-end'>";
    $output .=  displayMainItem(1) . "
            </div>
            <div class='col'>
                <dl>";
    $output .=      displaySubItems([2,3,4]) . "
                </dl>
            </div>
        </div>
        <div class='row' id='entrees'>
            <div class='row'>
                <div class='col text-align-center'>
                    <h2 class='section-title'>Entrees</h2>
                </div>
            </div>
            <div class='col feature-dish text-end'>";
    $output .=  displayMainItem(5) . "
            </div>
            <div class='col'>
                <dl>";
    $output .=  displaySubItems([6, 7, 8, 9]) . "
                </dl>
            </div>
        </div>
        <div class='row' id='sides'>
            <div class='row'>
                <div class='col text-align-center'>
                    <h2 class='section-title'>Sides</h2>
                </div>
            </div>
            <div class='col feature-dish text-end'>";
    $output .=  displayMainItem(10) . "
            </div>
            <div class='col'>
                <dl>";
    $output .=      displaySubItems([11, 12, 13]) . "
                </dl>
            </div>
        </div>
        <div class='row' id='desserts'>
            <div class='row'>
                <div class='col text-align-center'>
                    <h2 class='section-title'>Desserts</h2>
                </div>
            </div>
            <div class='col feature-dish text-end'>";
    $output .=  displayMainItem(14) . "
            </div>
            <div class='col'>
                <dl>";
    $output .=      displaySubItems([15, 16]) . "
                </dl>
            </div>
        </div>
        <div class='row' id='beverages'>
            <div class='row'>
                <div class='col text-align-center'>
                    <h2 class='section-title'>Beverages</h2>
                </div>
            </div>
            <div class='col feature-dish text-end'>";
    $output .=  displayMainItem(17) . "
            </div>
            <div class='col'>
                <dl>";
    $output .=      displaySubItems([18, 19, 20, 21]) . "
                </dl>
            </div>
        </div>
    }
    ";

    return $output;
}


$bestSeller = $core->getBestSeller();
$html = displayMenu();
echo json_encode([
    'html' => $html,
    'bestSeller' => $bestSeller['menu_item_name'],
]);