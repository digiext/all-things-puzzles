<?php

use puzzlethings\src\gateway\StatusGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$status = $_POST['status'];

$gateway = new StatusGateway($db);
$code = $gateway->delete($id);

// session_start();
if (!$code) {
    failAlert("Error while deleting status '$status'!");
} else {
    successAlert("Status '$status' (ID $id) has been deleted");
}

header("Location: status.php");
