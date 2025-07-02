<?php

use puzzlethings\src\gateway\PuzzleGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

$id = $_POST['id'];

$gateway = new PuzzleGateway($db);
$code = $gateway->delete($id);

session_start();
if (!$code) {
    failAlert("Error while deleting puzzle!");
} else {
    successAlert("Puzzle (ID $id) has been deleted");
}

header("Location: puzzleinv.php");
