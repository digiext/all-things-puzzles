<?php

use puzzlethings\src\gateway\UserPuzzleGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

$userinvid = $_GET['id'];

$gateway = new UserPuzzleGateway($db);
$code = $gateway->delete($userinvid);

session_start();
if ($puzzle === false) {
    failAlert("Puzzle Not Removed!");
} else {
    successAlert("Puzzle removed from user inventory");
}

header("Location: userinvadd.php");
