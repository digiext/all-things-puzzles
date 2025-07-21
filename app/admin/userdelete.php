<?php

use puzzlethings\src\gateway\UserGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$user = $_POST['user'];

// Start new user gateway
$gateway = new UserGateway($db);

// Count admin records
$records = $gateway->countAdmin();

// Only allow delete if this is not the last admin
if ($records > 1) {

    $code = $gateway->delete($id);
} else {
    failAlert('You can not delete the last admin');
}

// session_start();
if (!$code) {
    failAlert("Error while deleting user '$user'!");
} else {
    successAlert("User '$user' (ID $id) has been deleted");
}

header("Location: users.php");
