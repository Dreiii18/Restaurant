<?php
if (isset($_COOKIE['cartItems'])) {
    unset($_COOKIE['cartItems']);
    setcookie('cartItems', '', time() - 3600, '/CPSC2221/Restaurant'); // empty value and old timestamp
}

session_start();
session_destroy();
header("Location: login.php");