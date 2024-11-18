<?php
require_once  dirname(__FILE__) . '/config/config.php';
require_once $config['path'] . '/backend/core.php';
require_once dirname(__FILE__) . '/frontend/pages.php';

$core = new Core();

$page = isset($_REQUEST['page']) && array_key_exists($_REQUEST['page'], $pages) ? $_REQUEST['page'] : 'register';
$_REQUEST['page'] = $page;

$pageTitles = [
    'register' => 'Register Page',
];

$title = $pageTitles[$page] ?? 'Home Page';

$css_files = array_merge($pages['main']['css'], $pages[$page]['css']);
$js_files  = array_merge($pages['main']['js'],  $pages[$page]['js']);

foreach ($css_files as $css) {
    echo "<link media='all' type='text/css' rel='stylesheet' href='frontend/css/{$css}?t=".time()."' />\n";
}
foreach ($js_files as $js) {
    echo "<script type='text/javascript'  src='frontend/js/{$js}?t=".time()."' ></script>\n";
}

?>

<div id="initForms">
    <section class="vh-200 gradient-custom">
        <div class="container-xlg py-5 h-100">
            <form id="registerForm">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <!-- LEFT FORM -->
                    <div class="col-md-3 d-none" id="address-form">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                            <h4 class="fw-bold mb-4">Address Details</h4>
                                <div class="row">
                                    <div data-mdb-input-init class="form-outline form-white mb-4 col-md-6">
                                        <input type="text" id="typeHousenumberX" name="housenumber" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeHousenumberX">House Number</label>
                                    </div>
                                    <div data-mdb-input-init class="form-outline form-white mb-4 col-md-6">
                                        <input type="text" id="typeStreetnumberX" name="streetnumber" class="form-control form-control-lg" />
                                        <label class="form-label" for="typestreetnumberX">Street Number</label>
                                    </div>
                                </div>
                                <div class="form-outline form-white mb-4">
                                    <input type="text" id="typeStreetnameX" name="streetname" class="form-control form-control-lg" />
                                    <label class="form-label" for="typeStreetnameX">Street Name</label>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="form-outline form-white mb-4 col-md-6 ">
                                        <input type="text" id="typePostalcodeX" name="postalcode" class="form-control form-control-lg" />
                                        <label class="form-label" for="typePostalcodeX">Postal Code</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MAIN RIGHT -->
                    <div class="col-md-4">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <div class="mb-md-5 mt-md-4">
                                    <h2 class="fw-bold mb-4 text-uppercase">Sign Up Now</h2>
                                    <h4 class="fw-bold mb-4">Personal Information</h4>
                                    <div class="row">
                                        <div data-mdb-input-init class="form-outline form-white mb-4 col-md-6">
                                            <input type="text" id="typeFirstnameX" name="firstname" class="form-control form-control-lg" />
                                            <label class="form-label" for="typeFirstnameX">First Name</label>
                                        </div>
                                        <div data-mdb-input-init class="form-outline form-white mb-4 col-md-6">
                                            <input type="text" id="typeLastnameX" name="lastname" class="form-control form-control-lg" />
                                            <label class="form-label" for="typeLastnameX">Last Name</label>
                                        </div>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="text" id="typeUsernameX" name="username" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeUsernameX">Username</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="password" id="typePasswordX" name="password" class="form-control form-control-lg" />
                                        <label class="form-label" for="typePasswordX">Password</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="text" id="typePhonenumberX" name="phonenumber" class="form-control form-control-lg" />
                                        <label class="form-label" for="typePhonenumberX">Phone Number</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="" id="addAddress">
                                        <label class="form-check-label" for="addAddress">
                                            Add Address
                                        </label>
                                    </div>
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" value="" id="addPayment">
                                        <label class="form-check-label" for="addPayment">
                                            Add Payment Information
                                        </label>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-0">Already have an account? <a href="./login.php" class="text-white-50 fw-bold">Login</a>
                                        </p>
                                    </div>

                                    <div class="mb-0">
                                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5 mb-0" id="submit" type="submit">Register</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT FORM -->
                    <div class="col-md-3 d-none" id="payment-form">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <h4 class="fw-bold mb-4">Payment Information</h4>
                                <div class="row">
                                    <div data-mdb-input-init class="form-outline form-white mb-4 col-md-6">
                                        <select class="form-select" id="typePaymentmethodX" name="paymentmethod" aria-label="Default select example">
                                            <option selected>Credit Card</option>
                                            <option value="1">Debit Card</option>
                                        </select>
                                        <label class="form-label" for="typePaymentmethodX">Payment Method</label>
                                    </div>
                                    <div data-mdb-input-init class="form-outline form-white mb-4 col-md-6">
                                        <select class="form-select" id="typeCardtypeX" name="cardtype" aria-label="Default select example">
                                            <option selected>Visa</option>
                                            <option value="1">Mastercard</option>
                                        </select>
                                        <label class="form-label" for="typeCardtypeX">Card Type</label>
                                    </div>
                                </div>
                                <div class="form-outline form-white mb-4">
                                    <input type="text" id="typeCardnumberX" name="cardnumber" class="form-control form-control-lg" />
                                    <label class="form-label" for="typeCardnumberX">Card Number</label>
                                </div>
                                <div class="row">
                                    <div data-mdb-input-init class="form-outline form-white mb-4 col-md-7">
                                        <input type="month" id="typeExpirydateX" name="expirydate" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeExpirydateX">Expiry Date</label>
                                    </div>
                                    <div data-mdb-input-init class="form-outline form-white mb-4 col-md-5">
                                        <input type="text" id="typeCVVX" name="cvv" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeCVVX">CVV</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>