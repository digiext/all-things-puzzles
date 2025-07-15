<?php

use puzzlethings\src\gateway\PuzzleWishGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

$id = $_GET['id'];

$gateway = new PuzzleWishGateway($db);
$code = $gateway->delete($id);

// session_start();
if (!$code) {
    failAlert("Error while deleting puzzle!");
} else {
    successAlert("Puzzle (ID $id) has been deleted");
}

header("Location: puzzlewish.php");
