<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\CategoryGateway;
use puzzlethings\src\object\Category;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Add Category';
include '../header.php';
include '../nav.php';


?>

<div class="container">
    <h3>Fill in form below for adding a category</h3>
    <form class="row" action="categoriesaddc.php" method="post" name="categoriesaddc">
        <div class="mb-3">
            <label for="brand" class="form-label">Category</label>
            <input type="text" class="form-control" name="category" id="category">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
            <a class="btn btn-danger mb-3" name="cancel" href="categories.php">Cancel</a>
        </div>
    </form>
</div>