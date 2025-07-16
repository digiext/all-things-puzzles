<?php

use puzzlethings\src\gateway\DispositionGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$disposition = $_POST['disposition'];

$gateway = new DispositionGateway($db);
$code = $gateway->delete($id);

// session_start();
if (!$code) {
    failAlert("Error while deleting disposition '$disposition'!");
} else {
    successAlert("Disposition '$disposition' (ID $id) has been deleted");
}

header("Location: disposition.php");
