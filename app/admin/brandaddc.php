<?php

use puzzlethings\src\gateway\BrandGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {
    $brand = $_POST['brand'];

    $gateway = new BrandGateway($db);
    $code = $gateway->create($brand);

    // session_start();
    if (empty($code)) {
        failAlert("Brand Not Created!");
    } else {
        successAlert("Brand has been created");
    }

    header("Location: brands.php");
}
