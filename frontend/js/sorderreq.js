$(document).ready(function() {
    let actionType = "";
    let supplyOrders = "";

    getOrderRequests();

    handleOrderAction("approve");
    handleOrderAction("reject");

    $('#approve-all').on("click", function() {
        let allOrders = getAllOrders();

        showConfirmationModal("approve", allOrders);
    });

    $('#reject-all').on("click", function() {
        let allOrders = getAllOrders();

        showConfirmationModal("reject", allOrders);
    });

    $('#confirm-yes').on("click", function() {
        if (actionType === "approve") {
            updateOrderRequest(supplyOrders, "Approved");
        }

        if (actionType === "reject") {
            updateOrderRequest(supplyOrders, "Rejected");
        }

        $('#staticBackdrop').modal("hide");
    });

    $('#confirm-no').on("click", function() {
        $('#staticBackdrop').modal("hide");
    })

    function showConfirmationModal(action, supplyOrder) {
        actionType = action;
        supplyOrders = supplyOrder;

        let myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        myModal.show();
    }

    function handleOrderAction(action) {
        $(document).on("click", `[id^='btn-${action}-']`, function() {
            let orderItemDiv = $(this).parent().parent().parent(".order");
    
            let orderDetails = getOrderDetails(orderItemDiv);
            let supplyOrder = [orderDetails];
    
            showConfirmationModal(action, supplyOrder);
        })
    }
})


function updateOrderRequest(supplyOrder, status) {
    $.ajax({
        url: "frontend/ajax/updateOrderRequest.php",
        data: {
            'supplyOrders' : supplyOrder,
            'status' : status
        },
        beforeSend: function() {
            $('.loading-spiner-holder').show();
        },
        success: function(data) {
            $('.loading-spiner-holder').hide();
            if (data == 1) {
                getOrderRequests();
            } else {
                alert("An error occurred while processing your request.");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        } 
    })
}

function getOrderRequests() {
    $.ajax({
        url: "frontend/ajax/sorderreq.php",
        dataType: "json",
        success: function (data) {
            if (data == 'not_found') {
                window.location.href = '404.html';
            };
            $('#orders').html(data.html);
            if (data.status === "success") {
                attachCollapsibleListeners();
            } else {
                const noOrders = new bootstrap.Modal(document.getElementById('noOrders'));
                noOrders.show();
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        } 
    })
}

function attachCollapsibleListeners() {
    var coll = document.getElementsByClassName("collapsible");
    var i;
    
    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            } 
        });
    }
}

function getOrderDetails(orderDiv) {
    let orderId = orderDiv.attr('id').replace('order-', '');
    let orderDateTime = orderDiv.find('.flex-grow-1').text().replace("Date Ordered: ", "").trim();

    return {
        'orderId' : orderId,
        'orderDateTime' : orderDateTime
    }
}

function getAllOrders() {
    let allOrders = [];

    $('.order').each(function() {
        let orderDetails = getOrderDetails($(this));
        allOrders.push(orderDetails);
    });

    return allOrders;
}

