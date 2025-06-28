<?php
include 'util/function.php';

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Delete Puzzle';
include 'header.php';
include 'nav.php';
