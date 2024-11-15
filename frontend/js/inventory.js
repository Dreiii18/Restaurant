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
          // orderItems(orders);
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
          }
      });
  }
});

function displayItems() {
  $.ajax({
      url: "frontend/ajax/inventory.php",
      success: function(data) {
          $('#inventoryList').html(data);
      }
  })
}

function orderItems(orders) {
  $.ajax({
      url: "frontend/ajax/generateOrderSupply.php",
      data: {
          'orders' : orders,
      },
      success: function(data) {
          console.log(data)
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
      console.log("TRUE");
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
// function filterCategory() {
          
//     let dropdown, table, rows, cells, category, filter;
//     dropdown = document.getElementById("categoryDropdown");
//     table = document.getElementById("table");
//     rows = table.getElementsByTagName("tr");
//     filter = dropdown.value;

    
//     for (let row of rows) { 
//         cells = row.getElementsByTagName("td");
//         category = cells[2] || null; 
       
//         if (filter === "Category" || !category || (filter === category.textContent)) {
//         row.style.display = ""; 
//         }
//         else {
//         row.style.display = "none"; 
//         }
//     }
// }
// function filterSupplier() {
//     // Variables
//     let dropdown, table, rows, cells, category, filter;
//     dropdown = document.getElementById("supplierDropdown");
//     table = document.getElementById("table");
//     rows = table.getElementsByTagName("tr");
//     filter = dropdown.value;

//     // Loops through rows and hides those with countries that don't match the filter
//     for (let row of rows) { // `for...of` loops through the NodeList
//         cells = row.getElementsByTagName("td");
//         category = cells[3] || null; // gets the 2nd `td` or nothing
//         // if the filter is set to 'All', or this is the header row, or 2nd `td` text matches filter
//         if (filter === "Suppliers" || !category || (filter === category.textContent)) {
//         row.style.display = ""; // shows this row
//         }
//         else {
//         row.style.display = "none"; // hides this row
//         }
//     }
//     }
// $(document).ready(function () {
//     $('#show').on('click', function () {
//         $('.center').show();
//         $(this).hide();
//     });
    
//     $('#close').on('click', function () {
//         $('.center').hide();
//         $('#show').show();
//     });
// });

// $(document).ready(function () {
//     $('#show').on('click', function () {
//         $('.center').show();
//         $(this).hide();
//     });

//     $('#close').on('click', function () {
//         $('.center').hide();
//         $('#show').show();
//     });

// });

// function configureDropDownLists(catDropdown, item) {
//     var items = ['Select A Category'];
//     var meat = ['Porkchop', 'fillet mignon', 'Blue'];
//     var dairy = ['milk', 'egg'];
//     var produce = ['potato', 'onion', 'apple'];
//     var spices = ['rosemary', 'basil'];
  
//     switch (catDropdown.value) {
//       case 'Category':
//         item.options.length = 0;
//         for (i = 0; i < items.length; i++) {
//           createOption(item, items[i], items[i]);
//         }
//         break;
//       case 'Meat':
//         item.options.length = 0;
//         for (i = 0; i < meat.length; i++) {
//           createOption(item, meat[i], meat[i]);
//         }
//         break;
//       case 'Dairy':
//         item.options.length = 0;
//         for (i = 0; i < dairy.length; i++) {
//           createOption(item, dairy[i], dairy[i]);
//         }
//         break;
//       case 'Produce':
//         item.options.length = 0;
//         for (i = 0; i < produce.length; i++) {
//           createOption(item, produce[i], produce[i]);
//         }
//         break;
//       case 'Spices':
//         item.options.length = 0;
//         for (i = 0; i < spices.length; i++) {
//           createOption(item, spices[i], spices[i]);
//         }
//         break;
//       default:
//         item.options.length = 0;
//         break;
//     }
  
//   }
  
//   function createOption(catDropdown, text, value) {
//     var opt = document.createElement('option');
//     opt.value = value;
//     opt.text = text;
//     catDropdown.options.add(opt);
//   }

//   function addRow() {
//     const divEle = document.getElementById("inputFields");
//     divEle.innerHTML += `
// <div>
//   <label for="catDropdown" class="label-category">Category:</label>
//                 <select id="catDropdown" name="category" class="category" onchange="configureDropDownLists(this,document.getElementById('item'))">
//                     <option value="Category">Category</option>
//                     <option value="Meat">Meat</option>
//                     <option value="Dairy">Dairy</option>
//                     <option value="Produce">Produce</option>
//                     <option value="Spices">Spices/Dry</option>
//                 </select>

//                 <label for="item">Item:</label>
//                 <select id="item" name="item">
                    
//                 </select>
                
//                 <label for="quantity" class="label-quantity">Quantity:</label>
//                 <input type="number" name="quantity" class="quantity" size="2">
              
               
            
//                 <label for="supDropdown" class="label-supplier">Supplier:</label>
//                 <select id="supDropdown" name="supplier" class="supplier">
//                     <option>Suppliers</option>
//                     <option>Max's Meat Co</option>
//                     <option>Dairyland</option>
//                     <option>Joe's Farm</option>
//                     <option>Sam's Spices</option>
//                 </select>

//                 <br><br>
// </div>
// `;
//   }

// function configureDropDownLists(catDropdown, itemDropdownId) {
//   const item = document.getElementById(itemDropdownId);
//   const items = ['Select A Category'];
//   const meat = ['Porkchop', 'fillet mignon', 'Blue'];
//   const dairy = ['milk', 'egg'];
//   const produce = ['potato', 'onion', 'apple'];
//   const spices = ['rosemary', 'basil'];

//   item.options.length = 0; // Clear existing options

//   switch (catDropdown.value) {
//     case 'Category':
//       items.forEach(i => createOption(item, i, i));
//       break;
//     case 'Meat':
//       meat.forEach(i => createOption(item, i, i));
//       break;
//     case 'Dairy':
//       dairy.forEach(i => createOption(item, i, i));
//       break;
//     case 'Produce':
//       produce.forEach(i => createOption(item, i, i));
//       break;
//     case 'Spices':
//       spices.forEach(i => createOption(item, i, i));
//       break;
//     default:
//       break;
//   }
// }

// function createOption(dropdown, text, value) {
//   const opt = document.createElement('option');
//   opt.value = value;
//   opt.text = text;
//   dropdown.options.add(opt);
// }

// function addRow() {
//   const divEle = document.getElementById("inputFields");
//   const uniqueId = `row-${Date.now()}`;
//   const catDropdownId = `${uniqueId}-catDropdown`;
//   const itemDropdownId = `${uniqueId}-item`;

//   divEle.innerHTML += `
//       <div>
//           <button id="remove">X</button>
//           <label for="${catDropdownId}" class="label-category">Category:</label>
//           <select id="${catDropdownId}" name="category" class="category" 
//               onchange="configureDropDownLists(this, '${itemDropdownId}')">
//               <option value="Category">Category</option>
//               <option value="Meat">Meat</option>
//               <option value="Dairy">Dairy</option>
//               <option value="Produce">Produce</option>
//               <option value="Spices">Spices/Dry</option>
//           </select>

//           <label for="${itemDropdownId}">Item:</label>
//           <select id="${itemDropdownId}" name="item" class="item">
//               <!-- Options will be populated based on category selection -->
//           </select>
              
//           <label for="quantity" class="label-quantity">Quantity:</label>
//           <input type="number" name="quantity" class="quantity" size="2">
              
//           <label for="supDropdown" class="label-supplier">Supplier:</label>
//           <select id="supDropdown" name="supplier" class="supplier">
//               <option>Suppliers</option>
//               <option>Max's Meat Co</option>
//               <option>Dairyland</option>
//               <option>Joe's Farm</option>
//               <option>Sam's Spices</option>
//           </select>

//           <label class="cost">Cost: </label>
          
//       </div>
//   `;
// }
