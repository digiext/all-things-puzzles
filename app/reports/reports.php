<?php
global $db;
require_once '../util/function.php';
require_once '../util/constants.php';
require_once '../util/db.php';

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../index.php");
}

$title = 'Reports';
include '../header.php';
include '../nav.php';
?>

<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Reports</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="../home.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>
<hr>
<div class="container">
    <strong>Status Reports</strong>
    <div class="row mt-2">
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="todo.php">To Do</a>
            </div>
        </div>
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="inprogress.php">In Progress</a>
            </div>
        </div>
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="completed.php">Completed</a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <strong>Disposition Reports</strong>
    <div class="row mt-2">
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="keep.php">Keep</a>
            </div>
        </div>
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="sell.php">Sell</a>
            </div>
        </div>
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="trade.php">Trade</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="donate.php">Donate</a>
            </div>
        </div>
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="giveaway.php">Give Away</a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <strong>Misc Reports</strong>
    <div class="row mt-2">
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="loaned.php">Loaned Out</a>
            </div>
        </div>
        <div class="col text-center">
            <div class="card my-2 border-0">
                <a class="btn btn-secondary btn-lg" href="borrowed.php">Borrowed</a>
            </div>
        </div>
    </div>
</div>