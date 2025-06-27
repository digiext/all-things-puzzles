<?php

use puzzlethings\src\gateway\BrandGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_GET['id'];

$gateway = new BrandGateway($db);
$code = $gateway->delete($id);

session_start();
if ($code == false) {
    failAlert("Brand Not Deleted!");
} else {
    successAlert("Brand has been deleted");
}

header("Location: brands.php");
