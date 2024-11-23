<?php
require_once  dirname(__FILE__) . '/config/config.php';
require_once $config['path'] . '/backend/core.php';
require_once dirname(__FILE__) . '/frontend/pages.php';

$core = new Core();

if (isset($_COOKIE['cartItems'])) {
    unset($_COOKIE['cartItems']);
    setcookie('cartItems', '', time() - 3600); // empty value and old timestamp
}

$page = isset($_REQUEST['page']) && array_key_exists($_REQUEST['page'], $pages) ? $_REQUEST['page'] : 'login';
$_REQUEST['page'] = $page;

$pageTitles = [
    'login' => 'Login Page',
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

$invalidLogin = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_REQUEST['username'];
    $password = md5($_REQUEST['password']);
    
    $core->login($username, $password);
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header('Location: index.php');
        exit;
    } else {
        $invalidLogin = true;
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
                            <form method="POST" action="login.php">
                                <div class="mb-md-5 mt-md-4 pb-5">
                                    <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                    <p class="text-white-50 mb-5">Please enter your username and password!</p>
                                    <p id="invalid" style="display: <?php echo $invalidLogin ? 'block' : 'none'; ?>;">Invalid username or password</p>
                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="text" id="typeUsernameX" name="username" class="form-control form-control-lg" />
                                        <label class="form-label" for="typeUsernameX">Username</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <input type="password" id="typePasswordX" name="password" class="form-control form-control-lg" />
                                        <label class="form-label" for="typePasswordX">Password</label>
                                    </div>
                                    <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="./reset.php">Forgot password?</a></p>

                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
                                </div>
                            </form>
                            <div>
                                <p class="mb-0">Don't have an account? <a href="./register.php" class="text-white-50 fw-bold">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>