<?php

use puzzlethings\src\gateway\OwnershipGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {
    $ownership = $_POST['ownership'];

    $gateway = new OwnershipGateway($db);
    $code = $gateway->create($ownership);

    // session_start();
    if ($code == false) {
        failAlert("Ownership Not Created!");
    } else {
        successAlert("Ownership has been created");
    }

    header("Location: ownership.php");
}
