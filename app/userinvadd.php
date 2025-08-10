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

$title = 'Add/Remove User Puzzles';
include 'header.php';
include 'nav.php';

$options = [
    MAX_PER_PAGE => 1000
];

// Return all puzzles from master list
$gateway = new PuzzleGateway($db);
$allpuzzles = $gateway->findAll($options);

// Return all puzzles for user
$userid = getUserID();

$gateway = new UserPuzzleGateway($db);
$userpuzzles = $gateway->findByUserId($userid);

// If user puzzles have records subtract them from master list
if (!empty($userpuzzles)) {
    $userPuzIDs = [];
    foreach ($userpuzzles as $upuz) {
        array_push($userPuzIDs, $upuz->getPuzzle()->getId());
    }

    $puzzles = array_filter($allpuzzles,  fn($puz) => !in_array($puz->getId(), $userPuzIDs));
} else {
    $puzzles = $allpuzzles;
}
?>

<script src="scripts/puzzles.js"></script>

<div class="container-fluid mt-2 row justify-content-center">
    <div class="container my-2 col">
        <div class="hstack mb-2 justify-content-between">
            <h4>Master Inventory</h4>
            <div class="ma-buttons-toolbar"></div>
        </div>
        <table
            id="table"
            data-classes="table  table-bordered table-striped table-hover"
            data-toggle="table"
            data-pagination="true"
            data-search="true"
            data-page-list="10,25,50,100,all"
            data-buttons-toolbar=".ma-buttons-toolbar"
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
                    <th scope='row' class='text-center align-middle id''><img src='" . getThumbnail($puzzle->getPicture()) . "' class='img-fluid' alt='Puzzle image'></th>
                    <td class='align-middle name'>" . $puzzle->getName() . "</td>
                        <td class='align-middle name'>" . $puzzle->getPieces() . "</td>
                        <td class='text-center'><a class='btn btn-secondary ' href='userinvaddc.php?id=" . $puzzle->getId() . "'><i class='bi bi-plus'></a></td>
                    </tr>";
                } ?>
            </tbody>
        </table>
    </div>
    <div class="container my-2 col">
        <div class="hstack mb-2 justify-content-end">
            <h4 class="me-auto">User Inventory</h4>
            <div class="us-buttons-toolbar"></div>
            <a class="ms-2 btn btn-primary" href="home.php">Home</a>
        </div>
        <table
            id="table"
            data-classes="table  table-bordered table-striped table-hover"
            data-toggle="table"
            data-pagination="true"
            data-search="true"
            data-buttons-toolbar=".us-buttons-toolbar"
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
                <?php if (!empty($userpuzzles)) {
                    foreach ($userpuzzles as $userpuzzle) {
                        if (!($userpuzzle instanceof UserPuzzle)) continue;
                        echo
                        "<tr class='user-puzzle-row'>
                            <th scope='row' class='text-center align-middle''><img src='" . getThumbnail($userpuzzle->getPuzzle()->getPicture()) . "' class='img-fluid' alt='Puzzle image'></th>
                            <td class='align-middle name'>" . $userpuzzle->getPuzzle()->getName() . "</td>
                            <td class='align-middle name'>" . $userpuzzle->getPuzzle()->getPieces() . "</td>
                            <td class='text-center'><a class='btn btn-secondary ' href='userinvremove.php?id=" . $userpuzzle->getId() . "'><i class='bi bi-dash'></a></td>
                        </tr>";
                    }
                } ?>
            </tbody>
        </table>
    </div>
</div>