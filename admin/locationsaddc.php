<?php

use puzzlethings\src\gateway\LocationGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {
    $location = $_POST['location'];

    $gateway = new LocationGateway($db);
    $code = $gateway->create($location);

    session_start();
    if ($code == false) {
        failAlert("Location Not Created!");
    } else {
        successAlert("Location has been created");
    }

    header("Location: locations.php");
}
