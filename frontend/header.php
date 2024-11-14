<?php
require_once  dirname(__FILE__) . '/../config/config.php';
require_once $config['path'] . '/backend/core.php';

$core = new Core();

function profile() {
    if (isset($_SESSION['user'])) {
        echo "
            <li><a class='dropdown-item' href='#'>Check Profile</a></li>
            <li><hr class='dropdown-divider'></li>
            <li><a class='dropdown-item' href='./logout.php'>Log Out</a></li>
        ";
    } else {
        echo "
            <li><a class='dropdown-item' href='./login.php'>Log In</a></li>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <script src="https://kit.fontawesome.com/8fb4709d42.js" crossorigin="anonymous"></script>
    <link href="https://use.fontawesome.com/releases/v5.0.1/css/all.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary " id="mainNav">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">Hidden brand</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?page=o">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?page=r">Reservation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?page=i">Inventory</a>
                </li>
            </ul>
            <div class="d-flex">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i>
                            <?php 
                                echo isset($_SESSION['user']) ? htmlspecialchars($core->getCustomerName($_SESSION['user']['userid'])) : 'Profile'; 
                            ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php profile(); ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="cart-checkout">
                <i class="fa badge" value=0 data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">&#xf07a;</i>
            </div>
        </div>
    </nav>