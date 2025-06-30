<?php
include 'util/function.php';

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Home Page';
include 'header.php';
include 'nav.php';
?>

<br>
<h3 class="text-center">Puzzle Stats</h3>
<div class="container text-center">
    <div class="row g-2">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    5 Most Recent Puzzles
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">An item</li>
                    <li class="list-group-item">A second item</li>
                    <li class="list-group-item">A third item</li>
                    <li class="list-group-item">A fourth item</li>
                    <li class="list-group-item">A fifth item</li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    5 Most Recent Completed Puzzles
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">An item</li>
                    <li class="list-group-item">A second item</li>
                    <li class="list-group-item">A third item</li>
                    <li class="list-group-item">A fourth item</li>
                    <li class="list-group-item">A fifth item</li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Top 5 Highest Rated Puzzles
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">An item</li>
                    <li class="list-group-item">A second item</li>
                    <li class="list-group-item">A third item</li>
                    <li class="list-group-item">A fourth item</li>
                    <li class="list-group-item">A fifth item</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<br>
<h3 class="text-center">Puzzle Management</h3>
<div class="container text-center my-2">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    Add Puzzle
                </div>
                <a class="btn btn-secondary" href="puzzleadd.php" type="button">Add</a>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    Puzzle Inventory
                </div>
                <a class="btn btn-secondary" href="puzzleinv.php" type="button">Inventory</a>
            </div>
        </div>
    </div>
</div>
<br>
<h3 class="text-center">Administration</h3>
<div class="container text-center my-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Admin Area
                </div>
                <a class="btn btn-secondary" href="admin.php" type="button">Admin</a>
            </div>
        </div>
    </div>
</div>