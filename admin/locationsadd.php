<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\LocationGateway;
use puzzlethings\src\object\Location;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Add Location';
include '../header.php';
include '../nav.php';


?>

<div class="container">
    <h3>Fill in form below for adding a location</h3>
    <form class="row" action="locationsaddc.php" method="post" name="locationsaddc">
        <div class="mb-3">
            <label for="brand" class="form-label">Location</label>
            <input type="text" class="form-control" name="location" id="location">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
            <a class="btn btn-danger mb-3" name="cancel" href="locations.php">Cancel</a>
        </div>
    </form>
</div>