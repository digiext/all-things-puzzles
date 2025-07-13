<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\OwnershipGateway;
use puzzlethings\src\object\Ownership;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Add Ownership';
include '../header.php';
include '../nav.php';


?>

<div class="container">
    <h3>Fill in form below for adding a brand</h3>
    <form class="row" action="ownershipaddc.php" method="post" name="ownershipaddc">
        <div class="mb-3">
            <label for="brand" class="form-label">Location</label>
            <input type="text" class="form-control" name="ownership" id="ownership">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
            <a class="btn btn-danger mb-3" name="cancel" href="ownership.php">Cancel</a>
        </div>
    </form>
</div>