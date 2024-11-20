$(document).ready(function () {
    let rowCount = 1;
    let orders = {};
    let itemDetails = [];
    let categories = [];
    let suppliers = [];
    displayItems();

    // INVENTORY
    $(document).on('change', '#categoryDropdown', function () {
        filterCategory();
    });

    // SUPPLY ORDER
    $('#show').on('click', function () {
        displayOrderForm(rowCount);
    });
    
    $('#add-item').on('click', function() {
        rowCount++;
        displayOrderForm(rowCount);
    });

    $(document).on("change", "[id^='item-name-']", function () {
        const dropdown = $(this);
        const rowId = dropdown.attr("id").split("-")[2];
        const selectedItemId = dropdown.val(); 
        
        const mappedCategories = categories.map((category, index) => ({
            ...category,
            id: index + 1,
        }));

        // Get the details of the selected item
        const item = itemDetails.find(item => item.inventoryid == selectedItemId);
        
        if (item) {
            const categoryValue = mappedCategories.find(category => category.category === item.category)
            $(`#unit-price-${rowId}`).val(item.unit_price).prop("disabled", true);
            $(`#item-quantity-${rowId}`).val(0).prop("disabled", false);
            $(`#item-category-${rowId}`).val(categoryValue.id).prop("disabled", true);
            $(`#item-supplier-${rowId}`).val(0).prop("disabled", false);
            $(`#total-cost-${rowId}`).val(parseFloat(0).toFixed(2));
        } else {
            $(`#unit-price-${rowId}`).val("").prop("disabled", true);
            $(`#item-quantity-${rowId}`).val(0).prop("disabled", false);
            $(`#item-category-${rowId}`).val(0).prop("disabled", false);
            $(`#item-supplier-${rowId}`).val(0).prop("disabled", false);
            $(`#total-cost-${rowId}`).val(parseFloat(0).toFixed(2));
        }

        clearErrors();
        calculateGrandTotal();
    });

    $(document).on("input, change", "[id^='item-quantity-']", function() {
        const quantity = $(this);
        const row = quantity.closest('.item-row');
        const unitPrice = row.find("[id^='unit-price-']");
        const totalCost = row.find("[id^='total-cost-']");

        if (quantity.val() < 0) {
            quantity.val(0);
        }

        totalCost.empty();
        totalCost.val(calculateTotal(quantity.val(), unitPrice.val()));
        quantity.removeClass("invalid");
        calculateGrandTotal();
    }).on('keydown', function(event) {
        if (event.key === "-" || event.key === "e") {
            event.preventDefault();
        }
    });

    $(document).on("change", "[id^='item-category-']", function() {
        const dropdown = $(this);
        const selectedCategoryId = dropdown.val(); 
        const row = dropdown.closest('.item-row'); 
        const $itemNameDropdown = row.find("[id^='item-name-']"); 
    
        const mappedCategories = categories.map((category, index) => ({
            ...category,
            id: index + 1, 
        }));
    
        const categoryValue = mappedCategories.find(category => category.id == selectedCategoryId);
        const filteredItems = itemDetails.filter(item => item.category === categoryValue.category);
        $itemNameDropdown.empty().append('<option value="">Choose...</option>');
    
        filteredItems.forEach(item => {
            $itemNameDropdown.append(`<option value="${item.inventoryid}">${item.item_name}</option>`);
        });
        calculateGrandTotal();
    });

    $(document).on("click", "[id^='remove-item-']", function() {
        if (rowCount > 1) {
            const row = $(this).closest('.item-row')
            row.remove();
            rowCount--;
            calculateGrandTotal();
        }
    });


    $('#submit').on('click', function() {
        if (validate()) {
            orders = { ...orders, ...getOrderDetails(itemDetails, suppliers)};
            orderItems(orders);

            const modalElement = document.getElementById('exampleModalToggle');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();
            $('.item-row').remove();
        } else {
            $('.invalid').first().focus();
        }
    });

    function displayOrderForm() {
        $.ajax({
            url: "frontend/ajax/orderSupply.php",
            data: {
                'rowCount': rowCount,
            },
            dataType: 'json',
            success: function(data) {
                $('.order').append(data.html);
                itemDetails = data.items;
                categories = data.categories;
                suppliers = data.suppliers;
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error, status, xhr);
                alert("An error occurred while processing your request.");
            } 
        });
    }
});

function displayItems() {
    $.ajax({
        url: "frontend/ajax/inventory.php",
        success: function(data) {
            $('#inventoryList').html(data);
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        } 
    })
}

function orderItems(orders) {
    $.ajax({
        url: "frontend/ajax/generateOrderSupply.php",
        data: {
            'orders' : orders,
        },
        dataType: "json",
        success: function(data) {
            $('#supplyOrderModal').remove();
            $('#output').html(data.html);
            console.log(data.msg);

            const modal = new bootstrap.Modal(document.getElementById('supplyOrderModal'));
            modal.show();
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        } 
    });
}


function filterCategory() {    
    const filter = $("#categoryDropdown").val().toLowerCase();

    $("#inventoryList").find("tr:not(:first)").each(function () {
        const categoryCell = $(this).find("td:nth-child(3)").text().toLowerCase();
        if (filter === "" || categoryCell.includes(filter)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function getOrderDetails(itemDetails, suppliers) {
    const orderData = [];

    const mappedSuppliers = suppliers.map((supplier, index) => ({
        ...supplier,
        id: index + 1, 
    }));

    $(".item-row").each(function () {
        const itemNameId = $(this).find("select[name='item_name[]']").val();
        const quantity = $(this).find("input[name='item_quantity[]']").val();
        const supplierId = $(this).find("select[name='item_supplier[]']").val();
        const totalCost = $(this).find("input[name='total_cost[]']").val();

        const item = itemDetails.find(item => item.inventoryid == itemNameId);
        const supplier = mappedSuppliers.find(supplier => supplier.id == supplierId);

        orderData.push({
            itemName: item.item_name,
            unitPrice: item.unit_price,
            quantity: quantity,
            supplier: supplier.supplier_name,
            totalCost: totalCost,
        });
    });

    return orderData;
}

function calculateTotal(quantity, price) {
    let total = quantity * price;

    return total.toFixed(2);
}

function calculateGrandTotal() {
    let grandTotal = 0;
    $(".item-row").each(function () {
        grandTotal += parseFloat($(this).find("input[name='total_cost[]']").val());
    })

    if (isNaN(grandTotal)) {
        grandTotal = 0;
    }

    $('#grandTotal').find('span').html(grandTotal.toFixed(2));
}

function validate() {
    let isValid = true;
    $(".item-row").each(function() {
        const itemNameField = $(this).find("select[name='item_name[]']");
        const quantityField = $(this).find("input[name='item_quantity[]']")
        const categoryField = $(this).find("select[name='item_category[]']");
        const supplierField = $(this).find("select[name='item_supplier[]']");

        if (itemNameField.val() == 0 || itemNameField.val() === "") {
            itemNameField.addClass("invalid");
            isValid = false;
        } else {
            itemNameField.removeClass("invalid");
        }

        if (quantityField.val() == 0) {
            quantityField.addClass("invalid");
            isValid = false;
        } else {
            quantityField.removeClass("invalid");
        }

        if (categoryField.val() == 0 || categoryField.val() === "") {
            categoryField.addClass("invalid");
            isValid = false;
        } else {
            categoryField.removeClass("invalid");
        }

        if (supplierField.val() == 0 || supplierField.val() === "") {
            supplierField.addClass("invalid");
            isValid = false;
        } else {
            supplierField.removeClass("invalid");
        }
    })

    return isValid;
}

function clearErrors() {
    $(".item-row").each(function() {
        const itemNameField = $(this).find("select[name='item_name[]']");
        const quantityField = $(this).find("input[name='item_quantity[]']")
        const categoryField = $(this).find("select[name='item_category[]']");
        const supplierField = $(this).find("select[name='item_supplier[]']");

        itemNameField.removeClass("invalid");
        quantityField.removeClass("invalid");
        categoryField.removeClass("invalid");
        supplierField.removeClass("invalid");
    })
}