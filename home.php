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
<?php
if (isset($_SESSION['success'])) { ?>
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        <h4><?php
            echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div><?php
        } elseif (isset($_SESSION['fail'])) { ?>
    <div class='alert alert-danger alert-dismissible fade show' role="alert">
        <h4><?php
            echo $_SESSION['fail'];
            unset($_SESSION['fail']); ?>
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php }
?>

<h3>Puzzle Stats</h3>
<div class="container text-center">
    <div class=" row">
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
<h3>Puzzle Management</h3>
<div class="container text-center">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Add Puzzle
                </div>
                <button class="btn btn-secondary" href="#" type="button">Add</button>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Puzzle Inventory
                </div>
                <button class="btn btn-secondary" href="#" type="button">Inventory</button>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Remove Puzzle
                </div>
                <button class="btn btn-secondary" href="#" type="button">Remove</button>
            </div>
        </div>
    </div>
</div>
<div class="container text-center">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Admin Area
                </div>
                <button class="btn btn-secondary" href="admin.php" type="button">Admin</button>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Future Item
                </div>
                <button class="btn btn-secondary disabled" href="#" type="button">Future</button>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Future Item
                </div>
                <button class="btn btn-secondary disabled" href="#" type="button">Future</button>
            </div>
        </div>
    </div>
</div>