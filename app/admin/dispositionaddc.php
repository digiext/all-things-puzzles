<?php

use puzzlethings\src\gateway\DispositionGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {
    $disposition = $_POST['disposition'];

    $gateway = new DispositionGateway($db);
    $code = $gateway->create($disposition);

    // session_start();
    if ($code == false) {
        failAlert("Disposition Not Created!");
    } else {
        successAlert("Disposition has been created");
    }

    header("Location: disposition.php");
}
