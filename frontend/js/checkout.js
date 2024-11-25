const urlParams = new URLSearchParams(window.location.search);
let allowRedirect = false;
$(document).ready(function() {
    let transaction = {};
    let customerAddresses = [];
    let customerPayments = [];

    getCustomerInfo();

    function getCustomerInfo() {
        $.ajax({
            url: "frontend/ajax/populateCheckout.php",
            dataType: "json",
            beforeSend: function() {
                $('.loading-spiner-holder').show();
            },
            success: function(data) {
                if (data == 'not_found') {
                    window.location.href = '404.html';
                }

                customerAddresses = Array.isArray(data.addresses) ? data.addresses : [];
                customerPayments = Array.isArray(data.payment_infos) ? data.payment_infos : [];

                // check if customer has saved phone number
                if (data.phone_number !== "") {
                    $('#phone_num').val(data.phone_number);
                } else {
                    $('#phone_num').val("");
                }

                // check if the customer has more than 1 saved address information
                if (customerAddresses.length > 0) {
                    $('#addresses').show();
                    populateAddressOptions(customerAddresses);
                    populateAddressForm(customerAddresses[0]);
                } else {
                    $('#addresses').hide();
                }
                
                // check if the customer has more than 1 saved address information
                if (customerPayments.length > 0) {
                    $('#payment_infos').show();
                    // populatePaymentForm(customerPayments[0]);
                    checkPaymentMethod();
                } else {
                    $('#payment_infos').hide();
                }
                
                checkOrderMethod();
                $('.loading-spiner-holder').hide();
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error, status, xhr);
                alert("An error occurred while processing your request.");
            } 
        })
    }

    $('[name="order_method"]').on('change', checkOrderMethod);
    $('[name="payment_method"]').on('change', checkPaymentMethod);

    function checkOrderMethod() {
        if ($("input[name='order_method']:checked").attr("id") === 'delivery') {
            if (customerAddresses.length > 0) {
                toggleAddressFields(true, true);
                populateAddressForm(customerAddresses[0]);
            } else {
                toggleAddressFields(false, true);
                clearAddressForm();
            }
        } else {
            toggleAddressFields(true, false);
            clearAddressForm();
        }
    }

    function checkPaymentMethod() {
        const selectedPaymentMethod = $("input[name='payment_method']:checked").attr("id");

        if (selectedPaymentMethod !== 'cash') {
            if (customerPayments.length > 0) {
                populatePaymentOptions(customerPayments, selectedPaymentMethod);
                toggleCardFields(true);
                populatePaymentForm(customerPayments[0], selectedPaymentMethod);
                $('#payment_infos').show();
            } else {
                toggleCardFields(false);
                clearPaymentForm()
            }
        } else {
            $('#payment_infos').hide();
            toggleCardFields(true);
            clearPaymentForm();
        }
    }

    $('#address_list').on('click', '.dropdown-item', function() {
        if ($(this).attr("id") == "address-manual") {
            toggleAddressFields(false, true);
            clearAddressForm();
        } else {
            let index = $(this).attr("id").split('-')[1];
            toggleAddressFields(true, true);
            populateAddressForm(customerAddresses[index]);
        }
    });

    $('#payment_list').on('click', '.dropdown-item', function() {
        if ($(this).attr("id") == "payment-manual") {
            toggleCardFields(false);
            clearPaymentForm();
        } else {
            let index = $(this).attr("id").split('-')[1];
            toggleCardFields(true);
            populatePaymentForm(customerPayments[index], $("input[name='payment_method']:checked").attr("id"));
        }
    })

    $('[name="tip"]').on('input change', function() {
        handleCustomTip();
        transaction = calculateTotal();
    })

    $('#custom-field').on('keydown', function(event) {
        if (event.key === '-' || event.key === 'e') {
            event.preventDefault();
        }
    }).on('input', function() {
        let value = parseFloat($(this).val());
        if (value < 0) {
            $(this).val(0);
        }
        transaction = calculateTotal();
    });
    
    transaction = calculateTotal();

    $('#place-order').click(function () {
        if (validate()) {
            transaction = { ...calculateTotal(), ...getTransactionDetails()};
            addTransaction(transaction);    
        } else {
            $('.invalid').first().focus();
        }
    });
});

$(window).on("beforeunload", function() {
    const currentUrl = location.href;
    const nextUrl = document.activeElement?.href || location.href;

    if (nextUrl !== currentUrl) {
        if (!allowRedirect) {
            deleteOrder();
            $.removeCookie('cartItems');
            return "Are you sure you want to leave this page?"; 
        }
    }
});

function handleCustomTip() {
    const selectedTip = $("input[name='tip']:checked").attr("id");
    const customTax = $('#custom-field');

    if (selectedTip === "custom") {
        customTax.attr("disabled", false);
    } else {
        customTax.attr("disabled", true).val("");
    }
}

function toggleAddressFields(isDelivery, enableInstructions) {
    $('#house_num, #street_num, #street_name, #postal_code').attr("disabled", isDelivery);
    $('#special-instructions').attr("disabled", !enableInstructions);
}

function toggleCardFields(isCard) {
    $('#card_num, #expiry_date, #cvv').attr("disabled", isCard);
}

function populateAddressOptions(customerAddresses) {
    $('#address_list').empty();
    $('#address_list').append(`
        <li class="dropdown-item" name="selected_address" id="address-manual">Manual Input</li>
    `)
    customerAddresses.forEach((address, index) => {
        $('#address_list').append(`
           <li class="dropdown-item" name="selected_address" id="address-${index}">${address.postal_code}</li> 
        `);
    })
}

function populatePaymentOptions(customerPayments, selectedPaymentMethod) {
    $('#payment_list').empty();
    $('#payment_list').append(`
        <li class="dropdown-item" name="selected_payment" id="payment-manual">Manual Input</li> 
     `);
    customerPayments.forEach((payment, index) => {
        if (payment.payment_method.toLowerCase().replace(/\s+/g, '') === selectedPaymentMethod) {
            $('#payment_list').append(`
               <li class="dropdown-item" name="selected_payment" id="payment-${index}">${payment.card_number}</li> 
            `);
        }
    })
}

function populateAddressForm(customerAddress) {
    $('#house_num').val(customerAddress.house_number);
    $('#street_num').val(customerAddress.street_number);
    $('#street_name').val(customerAddress.street_name);
    $('#postal_code').val(customerAddress.postal_code);
}

function populatePaymentForm(customerPayment, selectedPaymentMethod) {
    if (customerPayment.payment_method.toLowerCase().replace(/\s+/g, '') === selectedPaymentMethod) {
        $('#card_num').val(parseInt(customerPayment.card_number));
        $('#expiry_date').val(customerPayment.expiry_date);
        $('#cvv').val(customerPayment.cvv);
    } else {
        toggleCardFields(false);
        clearPaymentForm();
    }
}

function clearAddressForm() {
    $('#house_num').val("");
    $('#street_num').val("");
    $('#street_name').val("");
    $('#postal_code').val("");
    $('#special-instructions').val("");
}

function clearPaymentForm() {
    $('#card_num').val("");
    $('#expiry_date').val("");
    $('#cvv').val("");
}

function addTransaction(transaction) {
    $.ajax({
        url: "frontend/ajax/checkout.php",  
        data: {
            'transaction' : transaction
        },
        beforeSend: function() {
            $('.loading-spiner-holder').show();
        },
        dataType: 'json',
        success: function(data) {
            allowRedirect = true;
            $('#output').html(data.html);
            console.log(data.msg);

            const modal = new bootstrap.Modal(document.getElementById('orderModal'));
            $('.loading-spiner-holder').hide();
            modal.show();

            $('#orderModal').on('hidden.bs.modal', function () {
                window.location.href = '?page=o';
                $.removeCookie('cartItems');
            });
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occurred while processing your request.");
        } 
    });
}

function deleteOrder() {
    $.ajax({
        url: "frontend/ajax/removeOrder.php",
        data: {
            "orderNumber": urlParams.get('order_number')
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error, status, xhr);
            alert("An error occured while processing your request.");
        }
    })
}

function calculateTotal() {
    let cartItems = JSON.parse($.cookie('cartItems'));
    let subTotal = 0;
    let taxCost = 0;
    let total = 0;
    let taxRate = 0.12;
    let tip = parseFloat($("input[name='tip']:checked").attr("id"))/100;
    
    // for custom tip
    if ($("input[name='tip']:checked").attr("id") == "custom") {
        tip = parseFloat($('#custom-field').val());
        if (isNaN(tip)) {
            tip = 0;
        }
    };
    cartItems.forEach(item => {
        subTotal += item.price * item.quantity;
    });

    if ($("input[name='tip']:checked").attr("id") != "custom") {
        tip = subTotal * tip;
    };

    taxCost = subTotal * taxRate;
    total = subTotal + taxCost + tip;

    $('#subtotal-cost').text("$" + roundToHundredths(subTotal));
    $('#tax-cost').text("$" + roundToHundredths(taxCost));
    $('#total-cost').text("$" + roundToHundredths(total));

    return {
        'orderNumber' : urlParams.get('order_number'),
        'orderType' : $("input[name='order_method']:checked").val(),
        'paymentType' : $("input[name='payment_method']:checked").val(),
        'subTotal' : subTotal,
        'tip' : tip,
        'taxCost' : taxCost,
        'total' : total
    };
}

function getTransactionDetails() {
    let phoneNumber = $("#phone_num").val().trim().replace(/\D/g, '');
    if (phoneNumber.length === 10) {
        phoneNumber = '+1' + phoneNumber;
    } else if (phoneNumber.length === 11) {
        phoneNumber = '+' + phoneNumber;
    }

    return {
        'phoneNumber': phoneNumber,
        'cardNumber': $("#card_num").val().trim(),
        'expiryDate': $("#expiry_date").val(),
        'cvv': $("#cvv").val(),
        'houseNumber': $("#house_num").val() || 0,
        'streetNumber': $("#street_num").val() || 0,
        'streetName': $("#street_name").val(),
        'postalCode': $("#postal_code").val().trim(),
        'specialInstructions': $("#special-instructions").val()
    };
}

function roundToHundredths(num) {
    return Math.round(num * 100) / 100;
}

function validate() {
    let isValid = true;

    if ($("input[name='order_method']:checked").attr("id") === 'delivery') {
        const addressFields = ['#street_num', '#street_name', '#postal_code'];
        addressFields.forEach(selector => {
            const field = $(selector);
            if (field.val() === "") {
                field.addClass("invalid");
                isValid = false;
            } else {
                field.removeClass("invalid");
            }
        })

        const houseNumber = $('#house_num').val().trim();
        if (houseNumber !== "") {
            const houseNumberRegex = /\d+/;
            if (!houseNumberRegex.test(houseNumber)) {
                $('#house_num').addClass("invalid");
                isValid = false;
            } else {
                $('#house_num').removeClass("invalid");
            }
        }

        const streetNumber = $('#street_num').val().trim();
        const streetNumberRegex = /\d+/;
        if (streetNumber === "" || !streetNumberRegex.test(streetNumber)) {
            $('#street_num').addClass("invalid");
            isValid = false;
        } else {
            $('#street_num').removeClass("invalid");
        }

        const postalCode = $('#postal_code').val().trim();
        const postalCodeRegex = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/;
        if (!postalCodeRegex.test(postalCode)) {
            $('#postal_code').addClass("invalid");
            isValid = false;
        } else {
            $('#postal_code').removeClass("invalid");
        }
    }

    if ($("input[name='payment_method']:checked").attr("id") !== 'cash') {
        const paymentFields = document.querySelectorAll('.payment');
        paymentFields.forEach(selector => {
            const field = $(selector);
            if (field.val().trim() == "") {
                field.addClass("invalid");
                isValid = false;
            } else {
                field.removeClass("invalid");
            }
        })

        const cardNumber = $('#card_num').val().trim();
        const cardNumberRegex = /^\d{16}$/;
        if (!cardNumberRegex.test(cardNumber)) {
            $('#card_num').addClass("invalid");
            isValid = false;
        } else {
            $('#card_num').removeClass("invalid");
        }

        const cvv = $('#cvv').val().trim();
        const cvvRegex = /^\d{3}$/;
        if (!cvvRegex.test(cvv)) {
            $('#cvv').addClass("invalid");
            isValid = false;
        } else {
            $('#cvv').removeClass("invalid");
        }
    }

    const phoneNumber = $('#phone_num').val().trim();
    const phoneRegex = /^(\+1[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
    if (!phoneRegex.test(phoneNumber)) {
        $('#phone_num').addClass("invalid");
        isValid = false;
    } else {
        $('#phone_num').removeClass("invalid");
    }

    if (!isValid) {
        alert("Please fill out all required fields correctly.")
    }

    return isValid;
}