$(document).ready(function () {
    getPendingDeliveries();

    function getPendingDeliveries() {
        $.ajax({
            url: "frontend/ajax/delivery.php",
            dataType: 'json',
            success: function(data) {
                $('#pendingDeliveries').html(data);
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error, status, xhr);
                alert("An error occurred while processing your request.");
            } 
        });
    }

    $(document).on('click', '[id^="btn-"]', function () {
        let buttonId = $(this).attr('id');
        let deliveryKey = buttonId.replace('btn-', ''); 

        let deliveryItemDiv = $('#delivery-' + deliveryKey); 
        let targetDiv = $('#completedDeliveries'); 
        deliveryItemDiv.detach().appendTo(targetDiv);

        let deliveredButton = `<input type='button' id='btn-delivered-${deliveryKey}' class='btn btn-success' value='Delivered'>`;

        if ($(this).val() === "Accept") {
            $(this).val('In Transit');
            $(this).removeClass('btn-primary').addClass('btn-outline-warning').attr("disabled", true);
        }
            
        deliveryItemDiv.find('.accordion-body').append(deliveredButton);
    });

    $(document).on('click', '[id^="btn-delivered-"]', function() {
        let buttonId = $(this).attr('id');
        let deliveryKey = buttonId.replace('btn-delivered-', '');

        let deliveryItemDiv = $('#delivery-' + deliveryKey); 
        deliveryItemDiv.remove();
        updateDeliveryStatus(deliveryKey);
    })

});

function updateDeliveryStatus(delivery) {
    $.ajax({
        url: "frontend/ajax/updateDelivery.php",
        data: {
            delivery: delivery,
        },
        success: function(data) {
            alert("Delivery Completed!");
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        } 
    })
}
