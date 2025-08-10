<?php

use puzzlethings\src\gateway\UserPuzzleGateway;


global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

const UPLOAD_DIR = '/images/uploads/completed';
const UPLOAD_DIR_ABSOLUTE = __DIR__ . UPLOAD_DIR;

$id = $_GET['id'];

$gateway = new UserPuzzleGateway($db);
$completedpic = $gateway->findById($id)->getPicture();
if (file_exists(UPLOAD_DIR_ABSOLUTE . "/" . $completedpic)) {
    unlink(UPLOAD_DIR_ABSOLUTE . "/" . $completedpic);
} else {
    failAlert("Completed picture file was not found");
}

$code = $gateway->update($id, [
    UINV_COMPLETE_URL => null,
]);


// session_start();
if ($code === false) {
    failAlert("Completed picture was not deleted!");
} else {
    successAlert("Completed picture was deleted");
}

header("Location: userinv.php");
