<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Add Brand';
include '../header.php';
include '../nav.php';

$gateway = new BrandGateway($db);
$brands = $gateway->findAll();
?>

<div class="container">
    <h3>Fill in form below for adding a brand</h3>
    <form class="row" action="brandaddc.php" method="post" name="brandaddc">
        <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" class="form-control" name="brand" id="brand">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
            <a class="btn btn-danger mb-3" name="cancel" href="brands.php">Cancel</a>
        </div>
    </form>
</div>