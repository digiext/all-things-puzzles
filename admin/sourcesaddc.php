<?php

use puzzlethings\src\gateway\SourceGateway;

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {
    $source = $_POST['source'];

    $gateway = new SourceGateway($db);
    $code = $gateway->create($source);

    session_start();
    if ($code == false) {
        failAlert("Source Not Created!");
    } else {
        successAlert("Source has been created");
    }

    header("Location: sources.php");
}
