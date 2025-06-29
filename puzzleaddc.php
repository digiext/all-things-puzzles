<?php

use puzzlethings\src\gateway\PuzzleGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

if (isset($_POST['submit'])) {
    $puzname = $_POST['puzname'];
    $pieces = $_POST['pieces'];
    $brand = $_POST['brand'];
    $cost = $_POST['cost'];
    $acquired = $_POST['acquired'];
    $source = $_POST['source'];
    $upc = $_POST['upc'];
    $disposition = $_POST['disposition'];
    $location = $_POST['location'];


    $gateway = new PuzzleGateway($db);
    $code = $gateway->create($puzname, $pieces, $brand, $cost, $acquired, $source, $location, $disposition, $upc);

    session_start();
    if ($code === false) {
        failAlert("Puzzle Not Created!");
    } else {
        successAlert("Puzzle has been created");
    }

    header("Location: home.php");
}
