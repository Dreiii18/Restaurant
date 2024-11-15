<?php

// Everything relathing to each individual pages
DEFINE('DEFAULT_PAGE',"o");


$pages = [
    // Main page
    'main' => [
        'css' => ['bootstrap.min.css', 'main.css'],
        'js'  => ['jquery-3.7.1.min.js', 'bootstrap.bundle.min.js', 'jquery.cookie.js', 'main.js'],
    ],

    // Login page
    'login' => [
        'html' => ['login.php'],
        'css'  => ['login.css', 'bootstrap.min.css'],
        'js'   => ['jquery-3.7.1.min.js', 'bootstrap.bundle.min.js'],
    ],

    // Register page
    'register' => [
        'html' => ['register.php'],
        'css'  => ['register.css', 'bootstrap.min.css'],
        'js'   => ['register.js', 'hashes.js', 'jquery-3.7.1.min.js', 'bootstrap.bundle.min.js'],
    ],

    // Reset page
    'reset' => [
        'html' => ['reset.php'],
        'css'  => ['reset.css', 'bootstrap.min.css'],
        'js'   => ['reset.js', 'hashes.js', 'jquery-3.7.1.min.js', 'bootstrap.bundle.min.js'],
    ],
    
    // ==============
    // CUSTOMER PAGES
    // ==============

    // Menu page
    'm' => [
        'html' => ['menu.html'],
        'css'  => ['menu.css'],
        'js'   => ['menu.js'],
    ],

    // Customer page
    'c' => [
        'html' => ['customer.html'],
        'css'  => ['customer.css'],
        'js'   => ['customer.js'],
    ],

    // Order page
    'o' => [
        'html' => ['order.html'],
        'css'  => ['order.css'],
        'js'   => ['order.js'],
    ],

    // Checkout page
    'co' => [
        'html' => ['checkout.html'],
        'css'  => ['checkout.css'],
        'js'   => ['checkout.js'],
    ],

    // Reservation page
    'r' => [
        'html' => ['reservation.html'],
        'css'  => ['reservation.css'],
        'js'   => ['reservation.js'],
    ],

    // ==============
    // EMPLOYEE PAGES
    // ==============

    // Inventory page
    'i' => [
        'html' => ['inventory.html'],
        'css'  => ['inventory.css'],
        'js'   => ['inventory.js'],
    ],

    // Delivery page
    'd' => [
        'html' => ['delivery.html'],
        'css'  => ['delivery.css'],
        'js'   => ['delivery.js'],
    ],
];