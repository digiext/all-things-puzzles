<?php

use puzzlethings\src\gateway\StatusGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {
    $status = $_POST['status'];

    $gateway = new StatusGateway($db);
    $code = $gateway->create($status);

    // session_start();
    if ($code == false) {
        failAlert("Status Not Created!");
    } else {
        successAlert("Status has been created");
    }

    header("Location: status.php");
}
