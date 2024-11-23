$(document).ready(function () {
    $('footer').remove();
    adjustContentHeight();
    setupDate();
    getDeliveries();

    function getDeliveries() {
        $.ajax({
            url: "frontend/ajax/delivery.php",
            dataType: 'json',
            data: {
                'date': $('#deliveryDate').val()
            },
            success: function(data) {
                if (data == 'not_found') {
                    window.location.href = '404.html';
                };

                if (data.has_record) {
                    $('#pendingDeliveries').html(data.pending);
                    $('#completedDeliveries').html(data.in_transit);
                } else {
                    clearRecord();
                    $('#staticBackdrop1').remove();
                    $('#output').html(data.htmlNoDelivery);
                    const modal = new bootstrap.Modal(document.getElementById('noDeliveries'));
                    modal.show();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error, status, xhr);
                alert("An error occurred while processing your request.");
            } 
        });
    }

    $('#deliveryDate').on('change', function() {
        getDeliveries();
    })

    $(document).on('click', '.accordion-button', function () {
        if ($(this).hasClass('collapsed')) {
            $(this).css('background-color', 'rgb(51, 50, 47)'); 
            $(this).css('color', 'white'); 
        } else {
            $(this).css('background-color', 'rgb(51, 50, 47)'); 
            $(this).css('color', 'white'); 
        }
    });

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

function setupDate() {
    const today = new Date();
    const formattedDate = today.toLocaleDateString('en-CA');
    document.getElementById('deliveryDate').value = formattedDate;
    return formattedDate;
}

function clearRecord() {
    $('#pendingDeliveries').empty();
    $('#completedDeliveries').empty();
}

function adjustContentHeight() {
    const navHeight = $('nav').outerHeight(); 
    $('#content').css('min-height', `calc(100vh - ${navHeight}px)`);
    $('#content').css('height', `calc(100vh - ${navHeight}px)`); 
}