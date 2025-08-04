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

$options = [
    SORT => UINV_ID,
    SORT_DIRECTION => SQL_SORT_DESC,
];
$gateway = new UserPuzzleGateway($db);
$userpuzzles = $gateway->findByUserId($userid, $options) ?? [];
?>

<script src="scripts/userinv.js"></script>

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
        data-classes="table  table-bordered table-striped table-hover"
        data-toggle="table"
        data-pagination="true"
        data-search="false"
        data-buttons-toolbar=".buttons-toolbar"
        data-page-list="10,25,50,100,all"
        data-search-on-enter-key="false"
        data-id-field="id">
        <thead>
            <tr>
                <th scope="col" class="text-center visually-hidden">ID</th>
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
                <th scope="col" class="text-center">Completed Pic</th>
                <th scope="col" class="text-center">Edit</th>
            </tr>
        </thead>

        <tbody class="table-group-divider">
            <?php foreach ($userpuzzles as $userpuzzle) {
                if (!($userpuzzle instanceof UserPuzzle)) continue;
                echo
                "<tr class='user-puzzle-row'>
                        <th scope='row' class='align-middle id visually-hidden'>" . $userpuzzle->getId() . "</th>
                        <td class='text-center align-middle''><img src='" . (empty(getThumbnail($userpuzzle->getPuzzle()->getPicture())) ? "no-image-dark.svg"  : "" . getThumbnail($userpuzzle->getPuzzle()->getPicture())) . "' alt='Puzzle image' height=100></th>
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
                        <td class='text-center align-middle picture''><a href='#picturelarge' data-bs-toggle='modal' data-bs-target='#picturelarge'><img src='" . (empty(getThumbnailCompleted($userpuzzle->getPicture())) ? "no-image-dark.svg"  : "" . getThumbnailCompleted($userpuzzle->getPicture())) . "' alt='Puzzle image' height=100></a></td>
                        <td class='text-center'><a class='btn btn-secondary id' href='userinvedit.php?id=" . $userpuzzle->getId() . "'><i class='bi bi-pencil'></a></td>
                    </tr>";
            } ?>
        </tbody>
    </table>
</div>



<div class="modal fade" id="picturelarge" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="picName" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="picName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="text" class="form-control visually-hidden" id="picId" name="id" value="" readonly>

                <img id="picPath" src="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>