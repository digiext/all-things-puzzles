<?php

use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\gateway\LocationGateway;
use puzzlethings\src\gateway\OwnershipGateway;
use puzzlethings\src\gateway\PuzzleWishGateway;
use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\SourceGateway;
use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\gateway\UserPuzzleGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

$id = $_GET['id'];

// Move to master puzzle list
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

// Move to user puzzle list
$userid = getUserID();

if ($code) {
    $puzid = $code->getId();
} else {
    failAlert("Puzzle was not created correctly");
    header("Location: puzzlewish.php");
}

$gateway = new StatusGateway($db);
$status = $gateway->findByName('To Do');

$missingpieces = 0;
$startdate = '';
$enddate = '';
$totaldays = 0;
$difficultyrating = 0;
$qualityrating = 0;
$overallrating = 0;

$gateway = new OwnershipGateway($db);
$ownership = $gateway->findByName('Owned');

$loanedoutto = '';

$gateway = new UserPuzzleGateway($db);
$ucode = $gateway->create($userid, $puzid, $status, $missingpieces, $startdate, $enddate, $totaldays, $difficultyrating, $qualityrating, $overallrating, $ownership, $loanedoutto);

// Delete wishlist record

if ($code && $ucode) {
    $gateway = new PuzzleWishGateway($db);
    $code = $gateway->delete($id);
}

session_start();
if (!$code && !$ucode) {
    failAlert("Error while moving puzzle!");
} else {
    successAlert("Puzzle has been moved");
}

header("Location: puzzlewish.php");
