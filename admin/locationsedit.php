<?php

use puzzlethings\src\gateway\LocationGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

$id = $_POST['id'];
$location = $_POST['location'];

$gateway = new LocationGateway($db);
$code = $gateway->updateDesc($id, $location);

session_start();
if (!$code) {
    failAlert("Error while updating location '$location'!");
} else {
    successAlert("Location '$location' (ID: $id) has been updated");
}

header("Location: locations.php");
