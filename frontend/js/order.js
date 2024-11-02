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
});

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