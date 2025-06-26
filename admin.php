<?php
include 'util/function.php';

//If Not Logged In Reroute to index.php
if (!isAdmin()) {
    header("Location: index.php");
}

$title = 'Admin Area';
include 'header.php';
include 'nav.php';

?>

<h3 class="container">Admin Area</h3>
<div class="container text-center my-2">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Brands
                </div>
                <a class="btn btn-primary" href="admin/brands.php" role="button">Brands</a>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Disposition
                </div>
                <a class="btn btn-primary" href="admin/disposition.php" role="button">Disposition</a>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Location
                </div>
                <a class="btn btn-primary" href="admin/location.php" role="button">Location</a>
            </div>
        </div>
    </div>
</div>
<div class="container text-center my-2">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Ownership
                </div>
                <a class="btn btn-primary" href="admin/ownership.php" role="button">Ownership</a>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Source
                </div>
                <a class="btn btn-primary" href="admin/source.php" role="button">Source</a>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Status
                </div>
                <a class="btn btn-primary" href="admin/status.php" role="button">Status</a>
            </div>
        </div>
    </div>
</div>