$(document).ready(function() {
    // let cartItems = updateCartDisplay();
    let cartItems = getCartItems();

    displayMenu();
    updateCosts();

    $('#items-ordered').on('click', '.add, .subtract', function() {
        const quantityInput = $(this).siblings('.quantity');
        let quantity = parseInt(quantityInput.val());

        if ($(this).hasClass('add')) {
            // item.quantity += 1;
            quantityInput.val(++quantity);
        } 

        // if ($(this).hasClass('subtract') && item.quantity > 0) {
        if ($(this).hasClass('subtract') && quantity > 0) {
            // item.quantity -= 1;
            quantityInput.val(--quantity);
        }

        const itemName = $(this).closest('.menu-item').data('item-name');
        const item = cartItems.find(item => item.name === itemName);

        if (item) {
            item.quantity = quantity;
            if (item.quantity === 0) {
                cartItems = cartItems.filter(item => item.name !== itemName);  // Remove item
            }
        }

        $.cookie('cartItems', JSON.stringify(cartItems));
        updateCartDisplay();
    });
    
    setTimeout(function() {
        $('.add-to-cart').on('click', function() {
            isCartOpen = true;
            let itemId = $(this).data('menu-id');
            let itemName = $(this).data('menu-name');
            let itemPrice = $(this).data('menu-price');
            addItemToCart(itemId, itemName, itemPrice);
            cartItems = getCartItems();
        });

        $('#checkout').on('click', function () {
            if (cartItems.length > 0) {
                addOrder(cartItems);  
            }
        });
    }, 500);

    function adjustFilterNavPosition() {
        const mainNavHeight = $('#mainNav').outerHeight() - 1;
        document.documentElement.style.setProperty('--main-nav-height', `${mainNavHeight}px`);
    }

    adjustFilterNavPosition();
    $(window).on('resize', adjustFilterNavPosition);
});

function addOrder(orders) {
    $.ajax({
        type: "post",
        url: "frontend/ajax/generateOrder.php",  
        data: {
            'orders' : orders
        },
        success: function(data){ 
            if (data !== false ){
                window.location.href = '?page=co&order_number=' + JSON.parse(data); 
            }
        } 
    });
}

function displayMenu() {
    $.ajax({
        url: "frontend/ajax/order.php",
        success: function(data) {
            $('#menu').html(data);
        },
    });

    updateCartDisplay();
}

function getCartItems() {
    let cartItems = [];
    if ($.cookie('cartItems')) {
        cartItems = JSON.parse($.cookie('cartItems'));
    }
    return cartItems;
}

function addItemToCart(itemId, name, price) {
    cartItems = getCartItems();

    let existingItem = cartItems.find(item => item.name === name);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        const newItem = {
            menuid: itemId, 
            name: name,
            price: price,
            quantity: 1
        };
        cartItems.push(newItem);
    }

    $.cookie('cartItems', JSON.stringify(cartItems));

    updateCartDisplay();
}

function updateCosts() {
    let subtotal = 0;

    $('#items-ordered .menu-item').each(function() {
        const price = parseFloat($(this).find('.ordered-item p').text().replace('$', ''));
        const quantity = parseInt($(this).find('.quantity').val());
        subtotal += price * quantity;
    });

    $('#subtotal-cost').text(`$${subtotal.toFixed(2)}`);
}

function updateCartButton() {
    let uniqueItemsCount = $('#items-ordered .menu-item').length;

    $('.cart-checkout .badge').attr('value', uniqueItemsCount);
}

function updateCartDisplay() {
    cartItems = getCartItems();
    let cartItemHTML = "";

    const cartItemsContainer = $('#items-ordered');
    cartItemsContainer.empty(); 

    if (cartItems.length > 0) {
        cartItems.forEach(item => {
            cartItemHTML += `
                <div class="menu-item" data-item-name="${item.name}">
                    <div class="ordered-item">
                        <h3>${item.name}</h3>
                        <p>$${item.price.toFixed(2)}</p>
                    </div>
                    <div class="item-qty">
                        <button type="button" class="subtract">-</button>
                        <input type="text" class="quantity" value="${item.quantity}">
                        <button type="button" class="add">+</button>
                    </div>
                </div>
            `;
        });
    } else {
        cartItemHTML = `
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
            </div
        `;
    }
    cartItemsContainer.append(cartItemHTML);

    updateCartButton();
    updateCosts();
    return cartItems;
}