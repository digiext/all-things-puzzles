<?php

use puzzlethings\src\gateway\BrandGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$brand = $_POST['brand'];

$gateway = new BrandGateway($db);
$code = $gateway->updateName($id, $brand);

session_start();
if (!$code) {
    failAlert("Error while updating brand '$brand'!");
} else {
    successAlert("Brand '$brand' (ID: $id) has been updated");
}

header("Location: brands.php");
