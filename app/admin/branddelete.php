<?php

use puzzlethings\src\gateway\BrandGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$brand = $_POST['brand'];

$gateway = new BrandGateway($db);
$code = $gateway->delete($id);

// session_start();
if (!$code) {
    failAlert("Error while deleting brand '$brand'!");
} else {
    successAlert("Brand '$brand' (ID $id) has been deleted");
}

header("Location: brands.php");
