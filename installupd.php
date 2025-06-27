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
require_once 'util/function.php';
require_once 'util/db.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $gateway = new UserGateway($db);
    $code = $gateway->create($username, $fullname, $email, $password, false, ADMIN_GROUP_ID);

    session_start();
    if ($code instanceof PDOException) {
        failAlert("Database Error: " . $code->getMessage());
    } elseif ($code === INVALID_USERNAME) {
        failAlert("Invalid username!");
    } elseif ($code === INVALID_EMAIL) {
        failAlert("Invalid email!");
    } elseif ($code === USERNAME_IN_USE) {
        failAlert("Username in use!");
    } elseif ($code === EMAIL_IN_USE) {
        failAlert("Email in use!");
    } elseif ($code === USERNAME_DB_ERROR || $code === EMAIL_DB_ERROR) {
        failAlert("Database error! Check your PHP Console for details!");
    } else {
        successAlert("User has been created");
    }

    header("Location: index.php");
}
