<?php

use puzzlethings\src\gateway\CategoryGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$category = $_POST['category'];

$gateway = new CategoryGateway($db);
$code = $gateway->delete($id);

session_start();
if (!$code) {
    failAlert("Error while deleting category '$category'!");
} else {
    successAlert("Category '$category' (ID $id) has been deleted");
}

header("Location: categories.php");
