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

$title = 'User Puzzle Inventory';
include 'header.php';
include 'nav.php';

$gateway = new PuzzleGateway($db);
$allpuzzles = $gateway->findAll($options);

$userid = getUserID();

$gateway = new UserPuzzleGateway($db);
$userpuzzles = $gateway->findByUserId($userid);

$userPuzIDs = [];
foreach ($userpuzzles as $upuz) {
    array_push($userPuzIDs, $upuz->getPuzzle()->getId());
}

$puzzles = array_filter($allpuzzles,  fn($puz) => !in_array($puz->getId(), $userPuzIDs));
?>

<script src="scripts/puzzles.js"></script>

<div class="container mb-2 mt-4 gap-3 d-flex justify-content-end align-items-center">
    <h3 class="text-center align-text-bottom me-auto">User Inventory Management</h3>

    <div>
        <a class="btn btn-primary" href="home.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>

<div class="container-fluid row justify-content-center">
    <div class="container my-2 col">
        <h4>Master Inventory</h4>
        <table
            id="table"
            data-classes="table table-dark table-bordered table-striped table-hover"
            data-toggle="table"
            data-pagination="true"
            data-search="false"
            data-buttons-toolbar=".buttons-toolbar"
            data-page-list="10,25,50,100,all"
            data-search-on-enter-key="false"
            data-id-field="id">
            <thead>
                <tr>
                    <th scope="col" class="text-center align-middle">Picture</th>
                    <th scope="col" class="col-11 align-middle" data-sortable="true" data-field="name">Name</th>
                    <th scope="col" class="text-center" data-sortable="true" data-field="pieces">Pieces</th>
                    <th scope="col" class="text-center">Add</th>
                </tr>
            </thead>

            <tbody class="table-group-divider">
                <?php foreach ($puzzles as $puzzle) {
                    if (!($puzzle instanceof Puzzle)) continue;
                    echo
                    "<tr class='puzzle-row'> 
                    <th scope='row' class='text-center align-middle id''><img src='images/uploads/thumbnails/" . $puzzle->getPicture() . "' class='img-fluid' alt='Puzzle image'></th>
                    <td class='align-middle name'>" . $puzzle->getName() . "</td>
                        <td class='align-middle name'>" . $puzzle->getPieces() . "</td>
                        <td class='text-center'><a class='btn btn-secondary ' href='userinvaddc.php?id=" . $puzzle->getId() . "'><i class='bi bi-plus'></a></td>
                    </tr>";
                } ?>
            </tbody>
        </table>
    </div>
    <div class="container my-2 col">
        <h4>User Inventory</h4>
        <table
            id="table"
            data-classes="table table-dark table-bordered table-striped table-hover"
            data-toggle="table"
            data-pagination="true"
            data-search="false"
            data-buttons-toolbar=".buttons-toolbar"
            data-page-list="10,25,50,100,all"
            data-search-on-enter-key="false"
            data-id-field="id">
            <thead>
                <tr>
                    <th scope="col" class="text-center align-middle">Picture</th>
                    <th scope="col" class="col-11 align-middle" data-sortable="true" data-field="name">Name</th>
                    <th scope="col" class="text-center" data-sortable="true" data-field="pieces">Pieces</th>
                    <th scope="col" class="text-center">Remove</th>
                </tr>
            </thead>

            <tbody class="table-group-divider">
                <?php foreach ($userpuzzles as $userpuzzle) {
                    if (!($userpuzzle instanceof UserPuzzle)) continue;
                    echo
                    "<tr class='user-puzzle-row'>
                        <th scope='row' class='text-center align-middle''><img src='images/uploads/thumbnails/" . $userpuzzle->getPuzzle()->getPicture() . "' class='img-fluid' alt='Puzzle image'></th>
                        <td class='align-middle name'>" . $userpuzzle->getPuzzle()->getName() . "</td>
                        <td class='align-middle name'>" . $userpuzzle->getPuzzle()->getPieces() . "</td>
                        <td class='text-center'><a class='btn btn-secondary ' href='userinvremove.php?id=" . $userpuzzle->getId() . "'><i class='bi bi-dash'></a></td>
                    </tr>";
                } ?>
            </tbody>
        </table>
    </div>
</div>