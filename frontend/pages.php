<?php

// Everything relathing to each individual pages
switch ($_SESSION['user']['roleid'] ?? 0) {
    case 3:
        $d = 'i';
        break;
    case 4:
        $d = 'd';
        break;
    default:
        $d = 'o';
}
DEFINE('DEFAULT_PAGE',$d);

$pages = [
    // Main page
    'main' => [
        'css' => ['bootstrap.min.css', 'main.css'],
        'js'  => ['jquery-3.7.1.min.js', 'bootstrap.bundle.min.js', 'jquery.cookie.js', 'main.js'],
        'access' => '',
    ],

    // Login page
    'login' => [
        'html' => ['login.php'],
        'css'  => ['login.css', 'bootstrap.min.css'],
        'js'   => ['jquery-3.7.1.min.js', 'bootstrap.bundle.min.js'],
        'access' => '',
    ],

    // Register page
    'register' => [
        'html' => ['register.php'],
        'css'  => ['register.css', 'bootstrap.min.css'],
        'js'   => ['register.js', 'hashes.js', 'jquery-3.7.1.min.js', 'bootstrap.bundle.min.js'],
        'access' => '',
    ],

    // Reset page
    'reset' => [
        'html' => ['reset.php'],
        'css'  => ['reset.css', 'bootstrap.min.css'],
        'js'   => ['reset.js', 'hashes.js', 'jquery-3.7.1.min.js', 'bootstrap.bundle.min.js'],
        'access' => '',
    ],
    
    // ==============
    // CUSTOMER PAGES
    // ==============

    // Order page
    'o' => [
        'html' => ['order.html'],
        'css'  => ['order.css'],
        'js'   => ['order.js'],
        'text' => 'Order',
        'access' => 'order',
    ],

    // Checkout page
    'co' => [
        'html' => ['checkout.html'],
        'css'  => ['checkout.css'],
        'js'   => ['checkout.js'],
        'text' => 'Checkout',
        'access' => 'checkout',
    ],

    // Reservation page
    'r' => [
        'html' => ['reservation.html'],
        'css'  => ['reservation.css'],
        'js'   => ['reservation.js'],
        'text' => 'Reservation',
        'access' => 'reservation',
    ],

    // ==============
    // EMPLOYEE PAGES
    // ==============

    // Inventory page
    'i' => [
        'html' => ['inventory.html'],
        'css'  => ['inventory.css'],
        'js'   => ['inventory.js'],
        'text' => 'Inventory',
        'access' => 'inventory',
    ],

    // Delivery page
    'd' => [
        'html' => ['delivery.html'],
        'css'  => ['delivery.css'],
        'js'   => ['delivery.js'],
        'text' => 'Delivery',
        'access' => 'delivery',
    ],

    // Supply Order Request page
    'sor' => [
        'html' => ['sorderreq.html'],
        'css' => ['sorderreq.css'],
        'js' => ['sorderreq.js'],
        'text' => 'Order Request',
        'access' => 'supply_order',
    ]
];