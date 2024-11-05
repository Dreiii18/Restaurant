let cartItems = [];
$(document).ready(function() {
    // wait for to card button before proceeding order
    var orders = []

    displayMenu();

    $('#checkout').click(function () {
        // passed from frontend
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
    
});

$(document).on('click', '.add-to-cart', function() {
    const menuId = $(this).closest('[data-menu-id]').data('menu-id');
    const existingItem = cartItems.find(item => item.menuId === menuId);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cartItems.push({ menuId: menuId, quantity: 1 });
    }

    console.log(cartItems);

    updateCartDisplay();
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

function displayMenu() {
    $.ajax({
        url: "frontend/ajax/order.php",
        success: function(data) {
            $('#menu').html(data);
        }
    })
}

function updateCartDisplay() {
    $('#items-ordered').empty(); 

    cartItems.forEach(item => {
        $('#items-ordered').append(`
            <div class="menu-item">
                <div class="ordered-item">
                    <h3>Tomahawk Steak</h3>
                    <p>$359.99</p>
                </div>
                <div class="item-qty">
                    <button type="button" class="subtract">-</button>
                    <input type="text" class="quantity" value="1">
                    <button type="button" class="add">+</button>
                </div>
            </div>
        `);
    });
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