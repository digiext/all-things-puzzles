<?php

use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\UserPuzzle;

global $db;
require_once '../util/function.php';
require_once '../util/constants.php';
require_once '../util/db.php';

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../index.php");
}

$title = 'Loaned Out Report';
include '../header.php';
include '../nav.php';

$gateway = new UserPuzzleGateway($db);
$puzzles = $gateway->loanedOut();

?>

<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Loaned Out Report</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="reports.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>

<div class="container my-2">
    <table
        id="table"
        data-classes="table table-bordered table-striped table-hover"
        data-toggle="table"
        data-pagination="true"
        data-search="false"
        data-buttons-toolbar=".buttons-toolbar"
        data-page-list="10,25,50,100,all"
        data-search-on-enter-key="false"
        data-show-print="true"
        data-id-field="id">
        <thead>
            <tr>
                <th scope="col" class="text-center align-middle" data-sortable="true" data-field="id">ID</th>
                <th scope="col" class="col align-middle">Picture</th>
                <th scope="col" class="col align-middle" data-sortable="true">Name</th>
                <th scope="col" class="col align-middle" data-sortable="true">Pieces</th>
                <th scope="col" class="col align-middle" data-sortable="true">Brand</th>
                <th scope="col" class="col align-middle" data-sortable="true">UPC</th>
                <th scope="col" class="col align-middle" data-sortable="true">Loaned Out To</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($puzzles as $puzzle) {
                if (!($puzzle instanceof UserPuzzle)) continue;
                echo
                "<tr>
                    <td scope='row' class='text-center align-middle id''>" . $puzzle->getPuzzle()->getId() . "</th>
                    <td class='text-center align-middle''><img src='" . (empty(getThumbnail($puzzle->getPuzzle()->getPicture())) ? "no-image-dark.svg"  : "" . getThumbnail($puzzle->getPuzzle()->getPicture())) . "' alt='Puzzle image' height=100></td>
                    <td class='align-middle name'>" . $puzzle->getPuzzle()->getName() . "</td>
                    <td class='align-middle name'>" . $puzzle->getPuzzle()->getPieces() . "</td>
                    <td class='align-middle name'>" . $puzzle->getPuzzle()->getBrand()->getName() . "</td>
                    <td class='align-middle name'>" . $puzzle->getPuzzle()->getUPC() . "</td>
                    <td class='align-middle name'>" . $puzzle->getLoaned() . "</td>
                </tr>";
            } ?>
        </tbody>
    </table>
</div>