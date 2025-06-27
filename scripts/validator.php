<?php
use puzzlethings\src\gateway\UserGateway;

global $db;
require_once __DIR__ . '/../util/function.php';
require_once __DIR__ . '/../util/db.php';

$gateway = new UserGateway($db);
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    if (isLoggedIn() && $username === ($gateway->findById(getUserID())->getUsername())) {
        echo "same";
        return;
    }

    echo json_encode(!$gateway->usernameInUse($username));
} else if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (isLoggedIn() && $email === ($gateway->findById(getUserID())->getEmail())) {
        echo "same";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "InvalidEmail";
        return;
    }

    echo json_encode(!$gateway->emailInUse($email));
}
