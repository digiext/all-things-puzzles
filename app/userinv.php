<?php
global $db;
require_once 'util/function.php';
require_once 'util/constants.php';
require_once 'util/db.php';

use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\UserPuzzle;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'User Puzzle Inventory';
include 'header.php';
include 'nav.php';

$userid = getUserID();

$gateway = new UserPuzzleGateway($db);
$userpuzzles = $gateway->findByUserId($userid);
?>

<div class="container-fluid mb-2 mt-4 gap-3 d-flex justify-content-end align-items-center">
    <h3 class="text-center align-text-bottom me-auto">User Inventory Management</h3>

    <div>
        <a class="btn btn-primary" href="home.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex my-2"></div>
    </div>
</div>
<div class="container-fluid my-2 col">
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
                <th scope="col" class="align-middle" data-sortable="true" data-field="name">Name</th>
                <th scope="col" class="text-center" data-sortable="true" data-field="pieces">Pieces</th>
                <th scope="col" class="text-center">Missing Pieces</th>
                <th scope="col" class="text-center" data-sortable="true">Status</th>
                <th scope="col" class="text-center" data-sortable="true">Start Date</th>
                <th scope="col" class="text-center">End Date</th>
                <th scope="col" class="text-center">Difficulty</th>
                <th scope="col" class="text-center">Quality</th>
                <th scope="col" class="text-center">Overall</th>
                <th scope="col" class="text-center">Ownership</th>
                <th scope="col" class="text-center">Loaned To</th>
                <th scope="col" class="text-center">Edit</th>
            </tr>
        </thead>

        <tbody class="table-group-divider">
            <?php foreach ($userpuzzles as $userpuzzle) {
                if (!($userpuzzle instanceof UserPuzzle)) continue;
                echo
                "<tr class='user-puzzle-row'>
                        <th scope='row' class='text-center align-middle''><img src='" . (empty(getThumbnail($userpuzzle->getPuzzle()->getPicture())) ? "no-image-dark.svg"  : "" . getThumbnail($userpuzzle->getPuzzle()->getPicture())) . "' alt='Puzzle image' height=100></th>
                        <td class='align-middle name'>" . $userpuzzle->getPuzzle()->getName() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getPuzzle()->getPieces() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getMissingPieces() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getStatus()->getDescription() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getStart() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getEnd() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getDifficulty() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getQuality() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getOverall() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getOwnership()->getDescription() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getLoaned() . "</td>
                        <td class='text-center'><a class='btn btn-secondary id' href='userinvedit.php?id=" . $userpuzzle->getId() . "'><i class='bi bi-pencil'></a></td>
                    </tr>";
            } ?>
        </tbody>
    </table>
</div>