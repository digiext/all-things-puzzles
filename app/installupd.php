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
    $code = $gateway->create($username, $fullname, $email, $password, false, GROUP_ID_ADMIN);


    $sql = "UPDATE setup SET installed = 1";


    // session_start();
    if ($code instanceof PDOException) {
        failAlertNoRedir("Database Error: " . $code->getMessage());
    } elseif ($code === INVALID_USERNAME) {
        failAlertNoRedir("Invalid username!");
    } elseif ($code === INVALID_EMAIL) {
        failAlertNoRedir("Invalid email!");
    } elseif ($code === USERNAME_IN_USE) {
        failAlertNoRedir("Username in use!");
    } elseif ($code === EMAIL_IN_USE) {
        failAlertNoRedir("Email in use!");
    } elseif ($code === USERNAME_DB_ERROR || $code === EMAIL_DB_ERROR) {
        failAlertNoRedir("Database error! Check your PHP Console for details!");
    } else {
        $db->query($sql);
        successAlert("User has been created");
    }

    header("Location: installation.php");
}
