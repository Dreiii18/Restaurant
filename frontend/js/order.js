<<<<<<< HEAD
$(function () {
    $(".add").click(function () {
        // Get the index of this button relative to other .add buttons
        var count = $(".add").index(this);

        // Get the current count value, convert it to an integer, add 1, and set it back
        var currentCount = parseInt($(".itemCount").eq(count).text(), 10);
        $(".itemCount").eq(count).text(currentCount + 1);
    });
    $(".subtract").click(function () {
        // Get the index of this button relative to other .subtract buttons
        var count = $(".subtract").index(this);

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

=======
$(document).ready(function() {
    // wait for to card button before proceeding order
    var orders = []
    $('#checkout').click(function () {
        // passed from frontend
    });
    orders = [
        {
            menuid: 1,
            quantity: 2
        },
        {
            menuid: 2,
            quantity: 3
        }
    ]
    addOrder(orders);   
    
>>>>>>> 5e485ecd997181ddd4b926fb007e8f2bf4ffd68c
    $(".badge").on("click", function() {
        $("#cart").toggle();
        $("#restaurant_background").toggle();
    }) 
});
<<<<<<< HEAD
=======

function addOrder(orders) {
    $.ajax({
        url: "frontend/ajax/order.php",  
        data: {
            // key   : value
            'orders' : orders
        },
        success: function(data){   
            $('#content').html(data);
        } 
    });
}

function displayOrder() {

}


// let cartItems = []; 

//     $('.add-button').on('click', function () {
//         const itemId = $(this).data('menuid');
//         const existingItem = cartItems.find(item => item.itemid === itemId);

//         if (existingItem) {
//             existingItem.quantity++;
//         } else {
//             cartItems.push({ itemid: itemId, quantity: 1 });
//         }

//         updateCartDisplay();
//     });

//     function updateCartDisplay() {
//         $('#cart-items').empty();
//         $.each(cartItems, function (index, item) {
//             $('#cart-items').append(`<li>Item ID: ${item.itemid}, Quantity: ${item.quantity}</li>`); 
//         });
//     }

//     $('#cart-button').on('click', function () {
//         $('#cart-popup').toggle(); 
//     });

//     // Close cart popup
//     $('#close-cart').on('click', function () {
//         $('#cart-popup').hide();
//     });
>>>>>>> 5e485ecd997181ddd4b926fb007e8f2bf4ffd68c
