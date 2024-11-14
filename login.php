<?php
session_start();
require_once  dirname(__FILE__) . '/config/config.php';
require_once $config['path'] . '/backend/core.php';

$core = new Core();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    
    $core->login($username, $password);
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header('Location: index.php');
        exit;
    } else {
        echo "test";
    }
}
// print_r($_SESSION['user']['username']);
?>

<form action="login.php" method="post">
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="password">Password</label>
    <input type="text" name="password" id="password">
    <input type="submit" value="Login">
    <a href="">Forgot Password?</a>
    <a href="./register.php">Create Account</a>
</form>