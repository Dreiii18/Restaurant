function filterCategory() {
          
    let dropdown, table, rows, cells, category, filter;
    dropdown = document.getElementById("categoryDropdown");
    table = document.getElementById("table");
    rows = table.getElementsByTagName("tr");
    filter = dropdown.value;

    
    for (let row of rows) { 
        cells = row.getElementsByTagName("td");
        category = cells[2] || null; 
       
        if (filter === "Category" || !category || (filter === category.textContent)) {
        row.style.display = ""; 
        }
        else {
        row.style.display = "none"; 
        }
    }
}
function filterSupplier() {
    // Variables
    let dropdown, table, rows, cells, category, filter;
    dropdown = document.getElementById("supplierDropdown");
    table = document.getElementById("table");
    rows = table.getElementsByTagName("tr");
    filter = dropdown.value;

    // Loops through rows and hides those with countries that don't match the filter
    for (let row of rows) { // `for...of` loops through the NodeList
        cells = row.getElementsByTagName("td");
        category = cells[3] || null; // gets the 2nd `td` or nothing
        // if the filter is set to 'All', or this is the header row, or 2nd `td` text matches filter
        if (filter === "Suppliers" || !category || (filter === category.textContent)) {
        row.style.display = ""; // shows this row
        }
        else {
        row.style.display = "none"; // hides this row
        }
    }
    }
$(document).ready(function () {
    $('#show').on('click', function () {
        $('.center').show();
        $(this).hide();
    });
    
    $('#close').on('click', function () {
        $('.center').hide();
        $('#show').show();
    });
});

$(document).ready(function () {
    $('#show').on('click', function () {
        $('.center').show();
        $(this).hide();
    });

    $('#close').on('click', function () {
        $('.center').hide();
        $('#show').show();
    });

});

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

function configureDropDownLists(catDropdown, itemDropdownId) {
  const item = document.getElementById(itemDropdownId);
  const items = ['Select A Category'];
  const meat = ['Porkchop', 'fillet mignon', 'Blue'];
  const dairy = ['milk', 'egg'];
  const produce = ['potato', 'onion', 'apple'];
  const spices = ['rosemary', 'basil'];

  item.options.length = 0; // Clear existing options

  switch (catDropdown.value) {
    case 'Category':
      items.forEach(i => createOption(item, i, i));
      break;
    case 'Meat':
      meat.forEach(i => createOption(item, i, i));
      break;
    case 'Dairy':
      dairy.forEach(i => createOption(item, i, i));
      break;
    case 'Produce':
      produce.forEach(i => createOption(item, i, i));
      break;
    case 'Spices':
      spices.forEach(i => createOption(item, i, i));
      break;
    default:
      break;
  }
}

function createOption(dropdown, text, value) {
  const opt = document.createElement('option');
  opt.value = value;
  opt.text = text;
  dropdown.options.add(opt);
}

function addRow() {
  const divEle = document.getElementById("inputFields");
  const uniqueId = `row-${Date.now()}`;
  const catDropdownId = `${uniqueId}-catDropdown`;
  const itemDropdownId = `${uniqueId}-item`;

  divEle.innerHTML += `
      <div>
          <label for="${catDropdownId}" class="label-category">Category:</label>
          <select id="${catDropdownId}" name="category" class="category" 
              onchange="configureDropDownLists(this, '${itemDropdownId}')">
              <option value="Category">Category</option>
              <option value="Meat">Meat</option>
              <option value="Dairy">Dairy</option>
              <option value="Produce">Produce</option>
              <option value="Spices">Spices/Dry</option>
          </select>

          <label for="${itemDropdownId}">Item:</label>
          <select id="${itemDropdownId}" name="item" class="item">
              <!-- Options will be populated based on category selection -->
          </select>
              
          <label for="quantity" class="label-quantity">Quantity:</label>
          <input type="number" name="quantity" class="quantity" size="2">
              
          <label for="supDropdown" class="label-supplier">Supplier:</label>
          <select id="supDropdown" name="supplier" class="supplier">
              <option>Suppliers</option>
              <option>Max's Meat Co</option>
              <option>Dairyland</option>
              <option>Joe's Farm</option>
              <option>Sam's Spices</option>
          </select>

          <br><br>
      </div>
  `;
}
