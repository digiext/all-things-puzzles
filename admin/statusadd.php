<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\object\Status;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Add Status';
include '../header.php';
include '../nav.php';


?>

<div class="container">
    <h3>Fill in form below for adding a brand</h3>
    <form class="row" action="statusaddc.php" method="post" name="statusaddc">
        <div class="mb-3">
            <label for="brand" class="form-label">Location</label>
            <input type="text" class="form-control" name="status" id="status">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
            <a class="btn btn-danger mb-3" name="cancel" href="status.php">Cancel</a>
        </div>
    </form>
</div>