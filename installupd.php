<?php

use puzzlethings\src\gateway\UserGateway;

use const puzzlethings\src\gateway\INVALID_USERNAME;

global $db;
require_once 'db.php';
require_once 'function.php';

var_dump($_POST);


if (isset($_POST['submit'])) {
    $username = $_POST['userid'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $gateway = new UserGateway($db);
    $code = $gateway->create($username, $fullname, $email, $password, false);

    if ($code == INVALID_USERNAME) {
        echo '<div class="alert alert-danger" role="alert>Invalid username!</div>';
    }

    echo $code;
}
