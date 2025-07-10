<?php

use puzzlethings\src\gateway\UserPuzzleGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

$puzzleid = $_GET['id'];
$userid = getUserID();
$status = 1;
$missingpieces = 0;
$start = '';
$end = '';
$totaldays = 0;
$difficultyrating = 0;
$qualityrating = 0;
$overallrating = 0;
$ownership = 1;
$loanoutto = '';

$gateway = new UserPuzzleGateway($db);
$code = $gateway->create($userid, $puzzleid, $status, $missingpieces, $start, $end, $totaldays, $difficultyrating, $qualityrating, $overallrating, $ownership, $loanoutto);

session_start();
if ($code === false) {
    failAlert("Puzzle Not Selected!");
} else {
    successAlert("Puzzle added to user inventory");
}

header("Location: userinvadd.php");
