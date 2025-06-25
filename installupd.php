<?php

use puzzlethings\src\gateway\UserGateway;

use const puzzlethings\src\gateway\INVALID_USERNAME;
use const puzzlethings\src\gateway\USERNAME_IN_USE;

global $db;
require_once 'db.php';
require_once 'function.php';

if (isset($_POST['submit'])) {
    $username = $_POST['userid'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $gateway = new UserGateway($db);
    $code = $gateway->create($username, $fullname, $email, $password, false);

    // Since there is no HTML on this page you need to find a way to "pass this up" to the installation/index page
    if ($code instanceof PDOException) {
        session_start();
        $_SESSION['fail'] = $code->getMessage();
        header("Location: index.php");
    } elseif ($code == USERNAME_IN_USE) {
        session_start();
        $_SESSION['fail'] = "Username in use!";
        header("Location: index.php");
    } else {
        session_start();
        $_SESSION['success'] = "User has been created";
        header("Location: index.php");
    }
}
