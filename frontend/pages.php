<?php

// Everything relathing to each individual pages
DEFINE('DEFAULT_PAGE',"m");


$pages = [
    // Main page
    'main' => [
        'css' => ['main.css', 'bootstrap.min.css'],
        'js'  => ['main.js', 'jquery-3.7.1.min.js', 'bootstrap.min.js'],
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

    // Customer page
    'o' => [
        'html' => ['order.html'],
        'css'  => ['order.css'],
        'js'   => ['order.js'],
    ]
];