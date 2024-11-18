<?php
session_start();
require_once  dirname(__FILE__) . '/config/config.php';
require_once $config['path'] . '/backend/core.php';
require_once dirname(__FILE__) . '/frontend/pages.php';

$core = new Core();

$page = isset($_REQUEST['page']) && array_key_exists($_REQUEST['page'], $pages) ? $_REQUEST['page'] : 'reset';
$_REQUEST['page'] = $page;

$pageTitles = [
    'reset' => 'Reset Page',
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

$invalidAccount = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_REQUEST['username'];
    $oldPassword = md5($_REQUEST['oldpassword']);
    $newPassword = md5($_REQUEST['newpassword']);
    
    if ($core->verifyUser($username, $oldPassword)) {
        $core->resetPassword($username, $oldPassword, $newPassword);
        header('Location: login.php');
        exit;
    } else {
        $invalidAccount = true;
    }
}
?>

<div id="initForms">
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form method="POST">
                                <div class="mb-md-5 mt-md-4">
                                    <h2 class="fw-bold mb-2 text-uppercase">Reset Password</h2>
                                    <p class="text-white-50 mb-5">Please enter your username, old password, and new password!</p>
                                    <p id="invalid" style="display: <?= $invalidAccount ? 'block' : 'none'; ?>;">Invalid username or password</p>
                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="text" id="typeUsernameX" name="username" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeUsernameX">Username</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="password" id="typeOldPasswordX" name="oldpassword" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeOldPasswordX">Old Password</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="newpassword" id="typeNewPasswordX" name="newpassword" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeNewPasswordX">New Password</label>
                                    </div>

                                    <div class="mb-0">
                                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5 mb-0" id="submit" type="submit">Change Password</button>
                                    </div>
                                </div>
                            </form>
                            <div>
                                <p class="mb-0">Remember your password? <a href="./register.php" class="text-white-50 fw-bold">Login</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>