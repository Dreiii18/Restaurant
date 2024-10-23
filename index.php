<?php
require_once  dirname(__FILE__) . '/config/config.php';
require_once $config['path'] . '/backend/core.php';

 $core = new Core();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
    // header('Location: login.php');
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout') {        
    $core->logout();
    header('Location: logout.php');
}

require_once dirname(__FILE__) . '/frontend/main.php';	
