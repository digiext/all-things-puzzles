<?php

use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\Ownership;
use puzzlethings\src\object\Status;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

$userinvid = $_POST['id'];
$status = $_POST['status'];
$startdate = $_POST['startDate'];
$enddate = $_POST['endDate'];
if ((($_POST['startDate']) != '1970-01-01') && (($_POST['endDate']) != '1970-01-01')) {
    $earlier = new DateTime($_POST['startDate']);
    $later = new DateTime($_POST['endDate']);

    $totaldays = $later->diff($earlier)->format("%a");
} else {
    $totaldays = 0;
}

$missingpieces = max($_POST['missingPieces'], 0.0);
$difficultyrating = min(max($_POST['difficulty'], 0.0), 5.0);
$qualityrating = min(max($_POST['quality'], 0.0), 5.0);
$overallrating = min(max($_POST['overall'], 0.0), 5.0);
$ownership = $_POST['ownership'];

$loanedoutto = $_POST['loanedoutto'];

$values = [
    UINV_STATUS => $status instanceof Status ? $status->getId() : $status,
    UINV_MISSING => $missingpieces,
    UINV_STARTDATE => $startdate,
    UINV_ENDDATE => $enddate,
    UINV_TOTALDAYS => $totaldays,
    UINV_DIFFICULTY => $difficultyrating,
    UINV_QUALITY => $qualityrating,
    UINV_OVERALL => $overallrating,
    UINV_OWNERSHIP => $ownership instanceof Ownership ? $ownership->getId() : $ownership,
    UINV_LOANED => $loanedoutto
];

$gateway = new UserPuzzleGateway($db);
$code = $gateway->update($userinvid, $values);


// session_start();
if ($code === false) {
    failAlert("User Record Not Selected!");
} else {
    successAlert("User puzzle record updated");
}

header("Location: userinv.php");
