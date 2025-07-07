<?php
global $db;
require_once 'util/function.php';
require_once 'util/constants.php';
require_once 'util/db.php';

use puzzlethings\src\gateway\PuzzleWishGateway;
use puzzlethings\src\object\PuzzleWish;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Puzzle Wishlist';
include 'header.php';
include 'nav.php';

$userid = getUserID();

$gateway = new PuzzleWishGateway($db);
$userpuzzles = $gateway->findByUserId($userid);
?>

<div class="container-fluid mb-2 mt-4 gap-3 d-flex justify-content-end align-items-center">
    <h3 class="text-center align-text-bottom me-auto">Puzzle Wishlist Management</h3>

    <div>
        <a class="btn btn-warning" href="puzzlewishadd.php">Add New</a>
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
                <th scope="col" class="align-middle" data-sortable="true" data-field="name">Name</th>
                <th scope="col" class="text-center" data-sortable="true" data-field="pieces">Pieces</th>
                <th scope="col" class="text-center">Brand</th>
                <th scope="col" class="text-center">UPC</th>
                <th scope="col" class="text-center">Edit</th>
                <th scope="col" class="text-center">Delete</th>
            </tr>
        </thead>

        <tbody class="table-group-divider">
            <?php foreach ($userpuzzles as $userpuzzle) {
                if (!($userpuzzle instanceof PuzzleWish)) continue;
                echo
                "<tr class='user-puzzle-row'>
                        <th scope='row' class='text-center align-middle''>" . ($userpuzzle->getName()) . "></th>
                        <td class='align-middle name'>" . $userpuzzle->getPieces() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getBrand() . "</td>
                        <td class='align-middle'>" . $userpuzzle->getUpc() . "</td>
                        <td class='text-center'><a class='btn btn-secondary id' href='puzzlewishedit.php?id=" . $userpuzzle->getId() . "'><i class='bi bi-pencil'></a></td>
                        <td class='text-center'><a class='btn btn-secondary id' href='puzzlewishdelete.php?id=" . $userpuzzle->getId() . "'><i class='bi bi-trash'></a></td>
                    </tr>";
            } ?>
        </tbody>
    </table>
</div>