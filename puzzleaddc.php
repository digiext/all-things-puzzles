<?php

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\gateway\SourceGateway;
use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\gateway\LocationGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

$brandname = $_POST['brandName'];
$sourcedesc = $_POST['sourceDesc'];
$dispositiondesc = $_POST['dispositionDesc'];
$locationdesc = $_POST['locationDesc'];

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

    if (!empty($brandname)) {
        $gateway = new BrandGateway($db);
        $code = $gateway->create($brandname);
        $brand = $code;
    }
    if (!empty($sourcedesc)) {
        $gateway = new SourceGateway($db);
        $code = $gateway->create($sourcedesc);
        $source = $code;
    }
    if (!empty($dispositiondesc)) {
        $gateway = new DispositionGateway($db);
        $code = $gateway->create($dispositiondesc);
        $disposition = $code;
    }
    if (!empty($locationdesc)) {
        $gateway = new LocationGateway($db);
        $code = $gateway->create($locationdesc);
        $location = $code;
    }

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
