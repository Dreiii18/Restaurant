<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

if (isset($_SESSION['user'])) {
    if (!$core->isAllowed('inventory')) {
        echo "not_found";
        die();
    } 
} else {
    echo "not_found";
    die();
}

$categories = $core->getItemDetails()[1];
$items = $core->getInventoryItems();

function displayCategories($categories) {
    foreach ($categories as $category) {
        echo "
            <option value='{$category['category']}'>{$category['category']}</option>
        ";
    }
}

function displayItems($items) {
    foreach ($items as $item) {
        echo "
            <tr>
                <td>{$item['item_name']}</td>
                <td>{$item['item_quantity']}</td>
                <td>{$item['category']}</td>
            </tr>
        ";
    }
}

?>

<thead class="table-dark align-middle">
    <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>
            <select id="categoryDropdown" class="form-select">
                <option value="0">Category</option>
                <?php displayCategories($categories)?>
            </select>
        </th>
    </tr>
</thead>
<tbody class="table-group-divider">
    <?php displayItems($items)?>
</tbody>