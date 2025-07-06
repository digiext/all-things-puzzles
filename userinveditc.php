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
$missingpieces = $_POST['missingpieces'];
$startdate = $_POST['startdate'];
$enddate = $_POST['enddate'];
if ((($_POST['startdate']) != '1970-01-01') && (($_POST['enddate']) != '1970-01-01')) {
    $earlier = new DateTime($_POST['startdate']);
    $later = new DateTime($_POST['enddate']);

    $totaldays = $later->diff($earlier)->format("%a");
} else {
    $totaldays = 0;
}

$difficultyrating = $_POST['difficulty'];
$qualityrating = $_POST['quality'];
$ownership = $_POST['ownership'];

$values = [
    USR_INV_STATUS => $status instanceof Status ? $status->getId() : $status,
    USR_INV_MISSING => $missingpieces,
    USR_INV_STARTDATE => $startdate,
    USR_INV_ENDDATE => $enddate,
    USR_INV_TOTALDAYS => $totaldays,
    USR_INV_DIFFICULTY => $difficultyrating,
    USR_INV_QUALITY => $qualityrating,
    USR_INV_OWNERSHIP => $ownership instanceof Ownership ? $ownership->getId() : $ownership,
];

echo var_dump($values);

$gateway = new UserPuzzleGateway($db);
$code = $gateway->update($userinvid, $values);


session_start();
if ($puzzle === false) {
    failAlert("User Record Not Selected!");
} else {
    successAlert("User puzzle record updated");
}

header("Location: userinv.php");
