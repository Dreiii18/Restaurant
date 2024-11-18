<?php

// Everything relathing to each individual pages
DEFINE('DEFAULT_PAGE',"m");


$pages = [
    // Main page
    'main' => [
        'css' => ['main.css', 'bootstrap.min.css'],
        'js'  => ['main.js', 'jquery-3.7.1.min.js', 'bootstrap.bundle.min.js', 'jquery.cookie.js'],
    ],
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

    //Inventory and order page
    'i' => [
        'html' => ['inventory.html'],
        'css' => ['inventory.css'],
        'js' => ['inventory.js'],
    ],

    // Supply Order Request page
    'sor' => [
        'html' => ['sorderreq.html'],
        'css'  => ['sorderreq.css'],
        'js'   => ['sorderreq.js'],
    ],
];