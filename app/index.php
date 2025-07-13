<?php
global $db;
include 'util/function.php';
include 'util/db.php';


$sql = "SELECT installed FROM setup";

$setup = $db->query($sql)->fetchColumn();

if ($setup == 0) {
    header("Location: installation.php");
}


//If Not Logged In Reroute to index.php
if (isLoggedIn()) {
    header("Location: home.php");
}

$title = 'All Things Puzzles';
include 'header.php';
include 'nav.php';
?>

<div class="container-fluid">
    <br>
    <h1>Welcome to All Things Puzzles!</h1>
    <br>
    <h4>All Things Puzzles is an inventory management system of your personal puzzle collection. </h4>
    <br>
    <h3>Features
        <ul>
            <li>Master Puzzle List</li>
            <li>Multi-user capable</li>
            <li>Wishlist</li>
        </ul>
    </h3>
    <br>
    <h3>To get started, sign up for an account using the Sign Up button in the upper right corner.</h3>
</div>