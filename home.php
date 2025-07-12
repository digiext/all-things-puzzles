<?php
global $db;
require_once 'util/function.php';
require_once 'util/constants.php';
require_once 'util/db.php';

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\object\Puzzle;
use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\UserPuzzle;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Home Page';
include 'header.php';
include 'nav.php';

$gateway = new PuzzleGateway($db);
$recents = $gateway->recent();

$gateway = new UserPuzzleGateway($db);
$completed = $gateway->completed();

$gateway = new UserPuzzleGateway($db);
$highestrated = $gateway->highestrated();

?>

<br>
<h3 class="text-center d-none d-sm-none">Puzzle Stats</h3>
<div class="container d-none d-sm-none text-center">
    <div class="row g-2">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    5 Most Recent Puzzles
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($recents as $puzzle) {
                        if (!($puzzle instanceof Puzzle)) continue;
                        echo
                        "<li class='list-group-item'>" . $puzzle->getName() . "</li>";
                    } ?>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    5 Most Recent Completed Puzzles
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($completed as $puzzle) {
                        if (!($puzzle instanceof UserPuzzle)) continue;
                        echo
                        "<li class='list-group-item'>" . $puzzle->getPuzzle()->getName() . "</li>";
                    } ?>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Top 5 Highest Rated Puzzles
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($highestrated as $puzzle) {
                        if (!($puzzle instanceof UserPuzzle)) continue;
                        echo
                        "<li class='list-group-item'>" . $puzzle->getPuzzle()->getName() . "</li>";
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container text-center">
    <div class="row g-5 justify-content-center">
        <div class="col-md-4 col-sm-12">
            <h3 class="text-center">Puzzle Management</h3>
            <div class="card my-2">
                <a class="btn btn-secondary btn-lg" href="puzzleadd.php" type="button">Add Puzzle</a>
            </div>
            <div class="card">
                <a class="btn btn-secondary btn-lg" href="puzzleinv.php" type="button">Puzzle Inventory</a>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <h3 class="text-center">User Puzzle Management</h3>
            <div class="card my-2">
                <a class="btn btn-secondary btn-lg" href="userinvadd.php" type="button">Add/Remove Puzzles</a>
            </div>
            <div class="card my-2">
                <a class="btn btn-secondary btn-lg" href="userinv.php" type="button">User Inventory</a>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <h3 class="text-center">Administration</h3>
            <div class="card my-2">
                <a class="btn btn-info btn-lg" href="admin.php" type="button">Admin Area</a>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container text-center">
    <div class="row g-5 justify-content-center">
        <div class="col-md-4 col-sm-12">
            <h3 class="text-center">Puzzle Wishlist</h3>
            <div class="card my-2">
                <a class="btn btn-secondary btn-lg" href="puzzlewish.php" type="button">Wishlist</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>