<?php

use puzzlethings\src\gateway\StatusGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$status = $_POST['status'];

$gateway = new StatusGateway($db);
$code = $gateway->updateDesc($id, $status);

session_start();
if (!$code) {
    failAlert("Error while updating status '$status'!");
} else {
    successAlert("Status '$status' (ID: $id) has been updated");
}

header("Location: status.php");
