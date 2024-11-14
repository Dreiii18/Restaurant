<?php
require_once(dirname(__FILE__) . "/../../config/config.php");
require_once($config["path"] . "/backend/core.php");

$core = new Core();

$rowCount = $_REQUEST['rowCount'];

[$items, $categories] = $core->getItemDetails();
$suppliers = $core->getSupplierNames();

function generateDropdown($colSize, $id, $name, $options, $label) {
    $itemRow = "
    <div class='{$colSize}'>
        <label for='{$id}' class='form-label'>{$label}</label>
        <select class='form-select' id='{$id}' name='{$name}[]'>
            <option value='0' selected>Choose...</option>";
            foreach ($options as $key => $value) {
                $itemRow .= "<option value='" . ($key + 1) . "'>{$value}</option>";
            }
    $itemRow .= "
        </select>
    </div>";
    return $itemRow;
}

function displayNewRow($rowCount, $items, $categories, $suppliers) {
    $itemRow = "<div class='row g-4 item-row'>";

    $itemRow .= generateDropdown("col-md-2", "item-name-{$rowCount}", "item_name", array_column($items, 'item_name'), "Item Name");

    $itemRow .= "
        <div class='col-md-2'>
            <label for='unit-price-{$rowCount}' class='form-label'>Unit Price</label>
            <input type='text' class='form-control' id='unit-price-{$rowCount}' name='unit_price[]' disabled readonly>
        </div>
        
        <div class='col-md-1'>
            <label for='item-quantity-{$rowCount}' class='form-label'>Quantity</label>
            <input type='number' class='form-control' id='item-quantity-{$rowCount}' name='item_quantity[]' value=0>
        </div>";

    $itemRow .= generateDropdown("col-md-2", "item-category-{$rowCount}", "item_category", array_column($categories, 'category'), "Category");

    $itemRow .= generateDropdown("col-md-2", "item-supplier-{$rowCount}", "item_supplier", array_column($suppliers, 'supplier_name'), "Supplier");
    
    $itemRow .= "
        <div class='col-md-2'>
            <label for='total-cost-{$rowCount}' class='form-label'>Total Cost</label>
            <input type='text' class='form-control' id='total-cost-{$rowCount}' name='total_cost[]' value=0.00 disabled readonly>
        </div>
        <div class='col-md-1 d-flex align-items-end justify-content-center'>
            <input type='button' class='btn btn-outline-danger' id='remove-item-{$rowCount}' value='X'>
        </div>
    </div>";
    

    return $itemRow;
}

$html = displayNewRow($rowCount, $items, $categories, $suppliers);

echo json_encode([
    'html' => $html,
    'items' => $items,
    'categories' => $categories,
    'suppliers' => $suppliers,
]);