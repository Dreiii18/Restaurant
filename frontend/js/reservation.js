$(document).ready(function() {
    let reservationDetails = {};

    adjustMainHeight();
    getCustomerInfo();

    function getCustomerInfo() {
        $.ajax({
            url: "frontend/ajax/populateReservation.php",
            dataType: 'json',
            success: function(data) {
                if (data.customerName !== "") {
                    $('#cust-name').val(data.customerName);
                } else {
                    $('#cust-name').val("");
                }

                if (data.customerPhoneNumber !== "") {
                    $("#cust-phone").val(data.customerPhoneNumber);
                } else {
                    $("#cust-phone").val("");
                }
            },
        })
    }    

    $('#reso-party').on('keydown', function(event) {
        if (event.key === "-" || event.key === "e" || event.key === ".") {
            event.preventDefault();
        }
    }).on('input', function() {
        let value = parseInt($(this).val());
        if (value < 0) {
            $(this).val(0);
        }
    });

    $('input').on('input', function() {
        $(this).removeClass("invalid");
    })

    $('#submit-button').click(function () {  
        reservationDetails = { ... reservationDetails, ...getReservationDetails()};
        addReservation(reservationDetails);
        if (validate()) {
        } else {
            $('.invalid').first().focus();
        }
    });

});

$(window).resize(adjustMainHeight);

function addReservation(reservation) {
    $.ajax({
        url: "frontend/ajax/reservation.php",  
        data: {
            'reservation' : reservation
        },
        success: function(data){   
            // console.log(data);
            $('#reservationMessage').html(data);
            const modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            modal.show();
        } 
    });
}

function getReservationDetails() {
    let phoneNumber = $("#cust-phone").val().trim().replace(/\D/g, '');
    if (phoneNumber.length === 10) {
        phoneNumber = '+1' + phoneNumber;
    } else if (phoneNumber.length === 11) {
        phoneNumber = '+' + phoneNumber;
    }

    return {
        'date': $('#reso-date').val(),
        'time': $('#reso-time').val(),
        'size': $('#reso-party').val(),
        'name': $('#cust-name').val(),
        'phone': phoneNumber,
    };
}

function validate() {
    let isValid = true;

    if ($('#reso-date').val() === "") {
        $('#reso-date').addClass("invalid");
        isValid = false;
    } else {
        $('#reso-date').removeClass("invalid");
    }

    if ($('#reso-time').val() === "") {
        $('#reso-time').addClass("invalid");
        isValid = false;
    } else {
        $('#reso-time').removeClass("invalid");
    }

    if ($('#reso-party').val() === "" || parseInt($('#reso-party').val()) === 0) {
        $('#reso-party').addClass("invalid");
        isValid = false;
    } else {
        $('#reso-party').removeClass("invalid");
    }

    if ($('#cust-name').val() === "") {
        $('#cust-name').addClass("invalid");
        isValid = false;
    } else {
        $('#cust-name').removeClass("invalid");
    }

    const phoneNumber = $('#cust-phone').val().trim();
    const phoneRegex = /^(\+1[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
    if ($('#cust-phone').val() === "" || !phoneRegex.test(phoneNumber)) {
        $('#cust-phone').addClass("invalid");
        isValid = false;
    } else {
        $('#cust-phone').removeClass("invalid");
    }
    
    
    return isValid;
}

function adjustMainHeight() {
    let navHeight = $('nav').outerHeight();
    let footerHeight = $('footer').outerHeight();
    let viewportHeight = $(window).height();

    let mainHeight = viewportHeight - footerHeight - navHeight;

    $('main').css('height', mainHeight + 'px');
}