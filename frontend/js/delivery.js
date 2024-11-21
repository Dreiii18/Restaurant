$(document).ready(function () {
    getPendingDeliveries();

    function getPendingDeliveries() {
        $.ajax({
            url: "frontend/ajax/delivery.php",
            dataType: 'json',
            success: function(data) {
                if (data == 'not_found') {
                    window.location.href = '404.html';
                };
                $('#pendingDeliveries').html(data.pending);
                $('#completedDeliveries').html(data.in_transit);
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error, status, xhr);
                alert("An error occurred while processing your request.");
            } 
        });
    }

    $(document).on('click', '[id^="pendingDeliveries-btn-"]', function () {
        let buttonId = $(this).attr('id');
        let deliveryKey = buttonId.replace('pendingDeliveries-btn-', ''); 

        let deliveryItemDiv = $('#pendingDeliveries-' + deliveryKey); 
        let targetDiv = $('#completedDeliveries'); 
        deliveryItemDiv.detach().appendTo(targetDiv);

        let deliveredButton = `<input type='button' id='btn-delivered-${deliveryKey}' class='btn btn-success' value='Delivered'>`;

        if ($(this).val() === "Accept") {
            $(this).val('In Transit');
            $(this).removeClass('btn-primary').addClass('btn-outline-warning').attr("disabled", true);
            updateDeliveryStatus(deliveryKey, "In Transit");
        }
            
        deliveryItemDiv.find('.accordion-body').append(deliveredButton);
    });

    $(document).on('click', '[id^="btn-delivered-"]', function() {
        let buttonId = $(this).attr('id');
        let deliveryKey = buttonId.replace('btn-delivered-', '');

        let deliveryItemDiv = $('#intransitDeliveries-' + deliveryKey); 
        deliveryItemDiv.remove();
        updateDeliveryStatus(deliveryKey, "Delivered");
    })
});

function updateDeliveryStatus(delivery, status) {
    $.ajax({
        url: "frontend/ajax/updateDelivery.php",
        data: {
            delivery: delivery,
            status: status,
        },
        dataType: "json",
        success: function(data) {
            $('#staticBackdrop').remove();
            $('#output').html(data.html);
            const modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            modal.show();
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        } 
    })
}
