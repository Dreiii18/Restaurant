$(function () {
    $(".add").click(function () {
        // Get the index of this button relative to other .add buttons
        var count = $(".add").index(this);
        console.log("ADD");

        // Get the current count value, convert it to an integer, add 1, and set it back
        var currentCount = parseInt($(".itemCount").eq(count).text(), 10);
        $(".itemCount").eq(count).text(currentCount + 1);
    });
    $(".subtract").click(function () {
        // Get the index of this button relative to other .subtract buttons
        var count = $(".subtract").index(this);
        console.log("SUBTRACT");
        // Get the current count value, convert it to an integer, subtract 1 if greater than 0, and set it back
        var currentCount = parseInt($(".itemCount").eq(count).text(), 10);
        if (currentCount > 0) {
            $(".itemCount").eq(count).text(currentCount - 1);
        }
    });
    $("toCart").click(function (){

    });
});

$(document).ready(function () {
    let totalItems = 0;

    // Function to add item to cart
    $(".add").click(function () {
        // Increment the total items counter each time an "add" button is clicked
        totalItems++;
        
        // Update the cart item count display
        $("#cartItemCount").text(totalItems);
    });

    // Show the cart when "toCart" button is clicked
    $("#toCart").click(function () {
        // Display the cart side panel (assuming it’s hidden with CSS initially)
        $("#cartSide").show();

        // Display the total items in the cart side panel
        $("#cartTotalItems").text("Total items in cart: " + totalItems);
    });
});
