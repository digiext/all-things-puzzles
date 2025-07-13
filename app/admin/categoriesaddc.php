<?php

use puzzlethings\src\gateway\CategoryGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {
    $category = $_POST['category'];

    $gateway = new CategoryGateway($db);
    $code = $gateway->create($category);

    session_start();
    if ($code == false) {
        failAlert("Category Not Created!");
    } else {
        successAlert("Category has been created");
    }

    header("Location: categories.php");
}
