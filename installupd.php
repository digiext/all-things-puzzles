<?php

use puzzlethings\src\gateway\UserGateway;

use const puzzlethings\src\gateway\INVALID_USERNAME;

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
        echo '<div class="alert alert-danger" role="alert>Database Error: ' . $code->getMessage() . '</div>';
    }

    if ($code == INVALID_USERNAME) {
        echo '<div class="alert alert-danger" role="alert>Invalid username!</div>';
    }
}
