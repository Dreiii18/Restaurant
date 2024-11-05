$(document).ready(function() {
    // wait for to card button before proceeding order
    $('#checkout').click(function () {
        // passed from frontend
    });
    let transaction = {
        orderNumber: 1,
        paymentType: 'Credit Card',
        subTotal: 211.96,
        tip: 25
    };

    addTransaction(transaction);    
});

function addTransaction(transaction) {
    $.ajax({
        url: "frontend/ajax/checkout.php",  
        data: {
            // key   : value
            'transaction' : transaction
        },
        success: function(data){   
            $('#content').html(data);
        } 
    });
}