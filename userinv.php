<?php
global $db;
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\object\Puzzle;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'User Inventory';
include 'header.php';
include 'nav.php';

$options = ["page" => 50, "maxperpage" => 8];

$gateway = new PuzzleGateway($db);
$puzzles = $gateway->findAll($options);

?>


<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">User Inventory</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="home.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>