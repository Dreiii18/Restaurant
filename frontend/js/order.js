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