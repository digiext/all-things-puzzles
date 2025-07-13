<?php

use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\gateway\LocationGateway;
use puzzlethings\src\gateway\PuzzleWishGateway;
use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\SourceGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

$id = $_GET['id'];

$gateway = new PuzzleWishGateway($db);
$puzzlewish = $gateway->findById($id);
$puzname = $puzzlewish->getName();
$pieces = $puzzlewish->getPieces();
$brand = $puzzlewish->getBrand()->getId();
$upc = $puzzlewish->getUpc();
$cost = 0;
$acquired = '';

$gateway = new DispositionGateway($db);
$disposition = $gateway->findByName('Keep');

$gateway = new LocationGateway($db);
$location = $gateway->findByName('Unknown');

$gateway = new SourceGateway($db);
$source = $gateway->findByName('New Purchase');

$gateway = new PuzzleGateway($db);
$code = $gateway->create($puzname, $pieces, $brand, $cost, $acquired, $source, $location, $disposition, $upc);

if ($code) {
    $gateway = new PuzzleWishGateway($db);
    $code = $gateway->delete($id);
}

session_start();
if (!$code) {
    failAlert("Error while moving puzzle!");
} else {
    successAlert("Puzzle has been moved");
}

header("Location: puzzlewish.php");
