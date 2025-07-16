<?php

use puzzlethings\src\gateway\UserGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$user = $_POST['user'];

$gateway = new UserGateway($db);
$code = $gateway->delete($id);

// session_start();
if (!$code) {
    failAlert("Error while deleting user '$user'!");
} else {
    successAlert("User '$user' (ID $id) has been deleted");
}

header("Location: users.php");
