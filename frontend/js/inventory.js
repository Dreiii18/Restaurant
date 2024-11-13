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
