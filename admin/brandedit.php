<?php

use puzzlethings\src\gateway\BrandGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_GET['id'];
$brand = $_POST['brand'];

$gateway = new BrandGateway($db);
$code = $gateway->updateName($id, $brand);

session_start();
if ($code == false) {
    failAlert("Brand " . $brand . " has not been updated");
} else {
    successAlert("Brand " . $brand . " has been updated");
}

header("Location: brands.php");
