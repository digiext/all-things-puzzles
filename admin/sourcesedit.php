<?php

use puzzlethings\src\gateway\SourceGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$source = $_POST['source'];

$gateway = new SourceGateway($db);
$code = $gateway->updateName($id, $source);

session_start();
if (!$code) {
    failAlert("Error while updating source '$source'!");
} else {
    successAlert("Source '$source' (ID: $id) has been updated");
}

header("Location: sources.php");
