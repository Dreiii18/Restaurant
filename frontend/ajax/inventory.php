<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

if (!$core->isAllowed('inventory')) {
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

<tr>
    <th width="150px">Item</th>
    <th>Quantity</th>
    <th>
        <select id="categoryDropdown">
            <option>Category</option>
            <?php displayCategories($categories)?>
        </select>
    </th>
</tr>
<?php displayItems($items)?>