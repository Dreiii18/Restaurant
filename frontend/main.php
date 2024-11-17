<?php

require_once dirname(__FILE__) . '/pages.php';

// Gets all the main page and content page css and js files ...	  
if (isset($_REQUEST['page']) && array_key_exists($_REQUEST['page'],$pages)) {
    $page = $_REQUEST['page'];
} else {
    $page = DEFAULT_PAGE;      
    $_REQUEST['page'] = $page;      
}

$css_files = array_merge($pages['main']['css'], $pages[$page]['css']);
$js_files  = array_merge($pages['main']['js'],  $pages[$page]['js']);

?>

<html>
    <head>
        <?php
            foreach ($css_files as $css) {
                echo "<link media='all' type='text/css' rel='stylesheet' href='frontend/css/{$css}?t=".time()."' />\n";
            }
            foreach ($js_files as $js) {
                echo "<script type='text/javascript'  src='frontend/js/{$js}?t=".time()."' ></script>\n";
            }
        ?>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="?page=m">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=c">Customer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=o">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=co">Checkout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=r">Reservations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=sor">Order Requests</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
            </nav>
        <div id="container">
            <?php
                foreach ($pages[$page]['html'] as $content) {
                    require "html/{$content}";
                }
            ?>    
        </div>
    </body>
</html>