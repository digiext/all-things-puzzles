<?php

use puzzlethings\src\gateway\UserGateway;

use const puzzlethings\src\gateway\{
    INVALID_USERNAME,
    INVALID_EMAIL,
    USERNAME_IN_USE,
    EMAIL_IN_USE,
    USERNAME_DB_ERROR,
    EMAIL_DB_ERROR
};

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

    session_start();
    if ($code instanceof PDOException) {
        $_SESSION['fail'] = $code->getMessage();
    } elseif ($code === INVALID_USERNAME) {
        $_SESSION['fail'] = "Invalid username!";
    } elseif ($code === INVALID_EMAIL) {
        $_SESSION['fail'] = "Invalid email!";
    } elseif ($code === USERNAME_IN_USE) {
        $_SESSION['fail'] = "Username in use!";
    } elseif ($code === EMAIL_IN_USE) {
        $_SESSION['fail'] = "Username in use!";
    } elseif ($code === USERNAME_DB_ERROR || $code === EMAIL_DB_ERROR) {
        $_SESSION['fail'] = "Database error! Check your PHP Console for details!";
    } else {
        $_SESSION['success'] = "User has been created";
    }

    header("Location: index.php");
}
