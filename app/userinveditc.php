<?php

use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\Ownership;
use puzzlethings\src\object\Status;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

const UPLOAD_DIR = '/images/uploads/completed';
const UPLOAD_DIR_ABSOLUTE = __DIR__ . UPLOAD_DIR;

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
$hasfile = isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK;
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
    UINV_LOANED => $loanedoutto,
];

$gateway = new UserPuzzleGateway($db);
$code = $gateway->update($userinvid, $values);


// session_start();
if ($code === false) {
    failAlert("User Record Not Selected!");
} else {
    if ($hasfile) {
        if (!file_exists(UPLOAD_DIR_ABSOLUTE)) {
            mkdir('images');
            mkdir('images/uploads');
            mkdir('images/uploads/completed');
        }

        $status = $_FILES['picture']['error'];
        $tmp = $_FILES['picture']['tmp_name'];

        if ($status !== UPLOAD_ERR_OK && $status !== UPLOAD_ERR_NO_FILE) {
            warningAlert(FILE_MESSAGES[$status], "userinvedit.php");
        }

        if ($status === UPLOAD_ERR_NO_FILE) {
            successAlert("User record has been updated!");
        }

        $filesize = filesize($tmp);
        if ($filesize > MAX_FILE_SIZE) {
            warningAlert("File too large! Must be under 5MB!", "userinvedit.php");
        }

        $mimetype = getMimeType($tmp);
        if (!in_array($mimetype, array_keys(ALLOWED_IMAGE_TYPES))) {
            warningAlert("Invalid file type! Must be a PNG or JPEG", "userinvedit.php");
        }

        $uploadedFile = str_replace([" ", "%"], "_", urlencode($gateway->findById($userinvid)->getPuzzle()->getName())) . "_" . $gateway->findById($userinvid)->getPuzzle()->getId() . "_complete" . '.' . ALLOWED_IMAGE_TYPES[$mimetype];
        $filepath = UPLOAD_DIR_ABSOLUTE . '/' . $uploadedFile;

        $success = move_uploaded_file($tmp, $filepath);
        if ($success) {
            $code = $gateway->update($userinvid, [
                UINV_COMPLETE_URL => $uploadedFile,
            ]);

            // var_dump($filepath, $code, $uploadedFile);
            successAlert("User record has been updated!");
        }
    } else successAlert("User record has been updated!");
}

header("Location: userinv.php");
