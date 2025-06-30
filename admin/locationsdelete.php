<?php

use puzzlethings\src\gateway\LocationGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$location = $_POST['location'];

$gateway = new LocationGateway($db);
$code = $gateway->delete($id);

session_start();
if (!$code) {
    failAlert("Error while deleting location '$location'!");
} else {
    successAlert("Location '$location' (ID $id) has been deleted");
}

header("Location: locations.php");
