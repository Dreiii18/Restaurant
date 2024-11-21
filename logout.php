<?php
require_once  dirname(__FILE__) . '/config/config.php';
require_once $config['path'] . '/backend/core.php';

$core = new Core();
if (isset($_COOKIE['cartItems'])) {
    unset($_COOKIE['cartItems']);
    setcookie('cartItems', '', time() - 3600); // empty value and old timestamp
}

$core->logout();
header("Location: login.php");