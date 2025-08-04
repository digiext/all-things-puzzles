<?php
include 'util/function.php';

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Admin Area';
include 'header.php';
include 'nav.php';

?>

<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Admin Area</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="home.php">Home</a>
    </div>
</div>
<br>
<p class="container my-2">User Changeable Options</p>
<div class="container text-center my-2">
    <div class="row">
        <div class="col">
            <div class="card">
                <a class="btn btn-primary" href="admin/brands.php" role="button">Brands</a>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <a class="btn btn-primary" href="admin/locations.php" role="button">Location</a>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <a class="btn btn-primary" href="admin/sources.php" role="button">Source</a>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-4">
            <div class="card">
                <a class="btn btn-primary" href="admin/categories.php" role="button">Categories</a>
            </div>
        </div>
    </div>
</div>
<br>

<?php
if (isAdmin()) {
    echo
    "<p class='container my-2'>Admin Only Options</p>
        <div class='container text-center my-2'>
            <div class='row'>
                <div class='col'>
                    <div class='card'>
                        <a class='btn btn-secondary' href='admin/disposition.php' role='button'>Disposition</a>
                    </div>
                </div>
                <div class='col'>
                    <div class='card'>
                        <a class='btn btn-secondary' href='admin/ownership.php' role='button'>Ownership</a>
                    </div>
                </div>
                <div class='col'>
                    <div class='card'>
                        <a class='btn btn-secondary' href='admin/status.php' role='button'>Status</a>
                    </div>
                </div>
            </div>
        </div>
        <div class='container text-center'>
    <div class='row justify-content-center'>
        <div class='col-4 my-3'>
            <div class='card'>
                <a class='btn btn-secondary' href='admin/users.php' role='button'>Users</a>
            </div>
        </div>
        <div class='col-4 my-3'>
            <div class='card'>
                <a class='btn btn-secondary' href='admin/migrate.php' role='button'>SQL Migration</a>
            </div>
        </div>
    </div>
</div>";
}

?>