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
require_once __DIR__ . '/../util/db.php';

$gateway = new UserGateway($db);
if (isset($_POST['username'])) {
    $username = $_POST['username'];


    echo json_encode(!$gateway->usernameInUse($username));
} else if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "InvalidEmail";
        return;
    }

    echo json_encode(!$gateway->emailInUse($email));
}
