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
    echo "
        <dl>
            <dt class='item_name' data-menu-id='{$id}' data-menu-price='{$menuPrice}'>
                <h2 >{$menuName}</h2>
            </dt>
            <dd>{$menuDescription}. \${$menuPrice}</dd>
            <dd>
                <button class='add-to-cart' data-menu-id='{$id}' data-menu-name='{$menuName}' data-menu-price='{$menuPrice}'>Add to Cart</button>
            </dd>
        </dl>
    ";
}

function displaySubItems($ids) {
    global $core;
    foreach($ids as $id) {
        [$menuName, $menuDescription, $menuPrice] = $core->getMenuItem($id);
        echo "
            <dt class='item_name' data-menu-id='{$id}' data-menu-name='{$menuName}' data-menu-price='{$menuPrice}'>{$menuName}</dt>
            <dd>{$menuDescription}. \${$menuPrice}</dd>
            <dd><button class='add-to-cart' data-menu-id='{$id}' data-menu-name='{$menuName}' data-menu-price='{$menuPrice}'>Add to Cart</button></dd>
        ";
    }
}
?>

<div class="row" id="starters">
    <div class="row">
        <div class="col text-align-center">
            <h2 class="section-title">Starters</h2>
        </div>
    </div>
    <div class="col feature-dish">
        <img src="frontend/images/truffleMacCheeseBalls.jpg" class="d-block w-100" alt="Deep fried truffle mac & cheese balls">
        <?php displayMainItem(1); ?>
    </div>
    <div class="col">
        <dl>
            <?php displaySubItems([2,3,4])?>
        </dl>
    </div>
</div>
<div class="row" id="entrees">
    <div class="row">
        <div class="col text-align-center">
            <h2 class="section-title">Entrees</h2>
        </div>
    </div>
    <div class="col feature-dish">
        <img src="frontend/images/tomahawkSteak.jpg" class="d-block w-100" alt="Tomahawk Steak">
        <?php displayMainItem(5)?>
    </div>
    <div class="col">
        <dl>
            <?php displaySubItems([6, 7, 8, 9])?>
        </dl>
    </div>
</div>
<div class="row" id="sides">
    <div class="row">
        <div class="col text-align-center">
            <h2 class="section-title">Sides</h2>
        </div>
    </div>
    <div class="col feature-dish">
        <img src="frontend/images/truffleFries.jpg" class="d-block w-100" alt="Truffle fries">
        <?php displayMainItem(10)?>
    </div>
    <div class="col">
        <dl>
            <?php displaySubItems([11, 12, 13])?>
        </dl>
    </div>
</div>
<div class="row" id="desserts">
    <div class="row">
        <div class="col text-align-center">
            <h2 class="section-title">Desserts</h2>
        </div>
    </div>
    <div class="col feature-dish">
        <img src="frontend/images/pecanPie.jpg" class="d-block w-100" alt="Pecan Pie">
        <?php displayMainItem(14)?>
    </div>
    <div class="col">
        <dl>
            <?php displaySubItems([15, 16])?>
        </dl>
    </div>
</div>
<div class="row" id="beverages">
    <div class="row">
        <div class="col text-align-center">
            <h2 class="section-title">Beverages</h2>
        </div>
    </div>
    <div class="col feature-dish">
        <img src="frontend/images/whiskeyFlight.jpg" class="d-block w-100" alt="Whiskey Flight">
        <?php displayMainItem(17)?>
    </div>
    <div class="col">
        <dl>
            <?php displaySubItems([18, 19, 20, 21])?>
        </dl>
    </div>
</div>