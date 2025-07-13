<?php

use puzzlethings\src\gateway\OwnershipGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$ownership = $_POST['ownership'];

$gateway = new OwnershipGateway($db);
$code = $gateway->delete($id);

session_start();
if (!$code) {
    failAlert("Error while deleting ownership '$ownership'!");
} else {
    successAlert("Ownership '$ownership' (ID $id) has been deleted");
}

header("Location: ownership.php");
