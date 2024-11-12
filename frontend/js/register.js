$(document).ready(function() {
    let customerDetails = {};

    $('#addAddress').on('change', function() {
        if ($('#addAddress').is(':checked')) {
            $('#address-form').removeClass('d-none');
        } else {
            $('#address-form').addClass('d-none');
        }
    });

    $('#addPayment').on('change', function() {
        if ($('#addPayment').is(':checked')) {
            $('#payment-form').removeClass('d-none');
        } else {
            $('#payment-form').addClass('d-none');
        }
    })

    document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault();
        if (validate()) {
            customerDetails = { ...customerDetails, ...getCustomerDetails()};
            addCustomerDetails(customerDetails);
        } else {
            $('.invalid').first().focus();
        }
    })
});

function addCustomerDetails(customerDetails) {
    $.ajax({
        url: "frontend/ajax/registerCustomer.php",
        data: {
            'customerDetails' : customerDetails
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if (data.success === true) {
                alert("Registered Successfully!");
                window.location.href = 'login.php';
            } else {
                alert("There was an issue with registering the details. Please try again.")
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        }
    })
}

function getCustomerDetails() {
    const MD5 = new Hashes.MD5();
    
    let phoneNumber = $("#typePhonenumberX").val().trim().replace(/\D/g, '');
    if (phoneNumber.length === 10) {
        phoneNumber = '+1' + phoneNumber;
    }

    return {
        'username': $("#typeUsernameX").val().trim(),
        'customerName': $("#typeFirstnameX").val().trim() + " " + $("#typeLastnameX").val().trim(),
        'password': MD5.hex($("#typePasswordX").val().trim()),
        'phoneNumber': phoneNumber,
        'houseNumber': $("#typeHousenumberX").val().trim(),
        'streetNumber': $("#typeStreetnumberX").val().trim(),
        'streetName': $("#typeStreetnameX").val().trim(),
        'postalCode': $("#typePostalcodeX").val().trim(),
        'paymentMethod': $("#typePaymentmethodX").val(),
        'cardType': $("#typeCardtypeX").val(),
        'cardNumber': $("#typeCardnumberX").val().trim(),
        'expiryDate': $("#typeExpirydateX").val().trim(),
        'cvv': $("#typeCVVX").val().trim(),
    }
}

function validate() {
    let isValid = true;

    const customerInformation = ['#typeFirstnameX', '#typeLastnameX', '#typeUsernameX', '#typePasswordX', '#typePhonenumberX'];
    customerInformation.forEach(selector => {
        const field = $(selector);
        if (field.val() === "") {
            field.addClass("invalid");
            isValid = false;
        } else {
            field.removeClass("invalid");
        }
    })

    const phoneNumber = $('#typePhonenumberX').val().trim();
    const phoneRegex = /^(\+1[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
    if (!phoneRegex.test(phoneNumber)) {
        $('#typePhonenumberX').addClass("invalid");
        isValid = false;
    } else {
        $('#typePhonenumberX').removeClass("invalid");
    }

    if ($('#addAddress').is(':checked')) {
        const addressInformation = ['#typeHousenumberX', '#typeStreetnumberX', '#typeStreetnameX', '#typePostalcodeX'];
        addressInformation.forEach(selector => {
            const field = $(selector);
            if (field.val() === "") {
                field.addClass("invalid");
                isValid = false;
            } else {
                field.removeClass("invalid");
            }
        })

        const houseNumber = $('#typeHousenumberX').val().trim();
        if (houseNumber !== "") {
            const houseNumberRegex = /\d+/;
            if (!houseNumberRegex.test(houseNumber)) {
                $('#typeHousenumberX').addClass("invalid");
                isValid = false;
            } else {
                $('#typeHousenumberX').removeClass("invalid");
            }
        }

        const streetNumber = $('#typeStreetnumberX').val().trim();
        const streetNumberRegex = /\d+/;
        if (streetNumber === "" || !streetNumberRegex.test(streetNumber)) {
            $('#typeStreetnumberX').addClass("invalid");
            isValid = false;
        } else {
            $('#typeStreetnumberX').removeClass("invalid");
        }

        const postalCode = $('#typePostalcodeX').val().trim();
        const postalCodeRegex = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/;
        if (!postalCodeRegex.test(postalCode)) {
            $('#typePostalcodeX').addClass("invalid");
            isValid = false;
        } else {
            $('#typePostalcodeX').removeClass("invalid");
        }
    }

    if ($('#addPayment').is(':checked')) {
        const paymentInformation = ['#typeCardnumberX', '#typeExpirydateX', '#typeCVVX'];
        paymentInformation.forEach(selector => {
            const field = $(selector);
            if (field.val() === "") {
                field.addClass("invalid");
                isValid = false;
            } else {
                field.removeClass("invalid");
            }
        })

        const cardNumber = $('#typeCardnumberX').val().trim();
        const cardNumberRegex = /^\d{16}$/;
        if (!cardNumberRegex.test(cardNumber)) {
            $('#typeCardnumberX').addClass("invalid");
            isValid = false;
        } else {
            $('#typeCardnumberX').removeClass("invalid");
        }

        const cvv = $('#typeCVVX').val().trim();
        const cvvRegex = /^\d{3}$/;
        if (!cvvRegex.test(cvv)) {
            $('#typeCVVX').addClass("invalid");
            isValid = false;
        } else {
            $('#typeCVVX').removeClass("invalid");
        }
    }

    if (!isValid) {
        alert("Please fill out all required fields correctly.")
    }

    return isValid;
}