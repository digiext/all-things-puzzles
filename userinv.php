<?php
global $db;
require_once 'util/function.php';
require_once 'util/constants.php';
require_once 'util/db.php';

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\object\Puzzle;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'User Puzzle Inventory';
include 'header.php';
include 'nav.php';

$page = $_GET['page'] ?? 1;
$maxperpage = $_GET['maxperpage'] ?? 10;
$sort = $_GET['sort'] ?? PUZ_ID;
$sortDirection = $_GET['sort_direction'] ?? SQL_SORT_ASC;

$query = [];
parse_str($_SERVER['QUERY_STRING'] ?? "", $query);

function queryForPage(int $page, array $extras = []): string
{
    global $query;
    return http_build_query(array_merge($query, ['page' => $page], $extras));
}

$options = [
    PAGE => $page - 1,
    MAX_PER_PAGE => $maxperpage,
    SORT => $sort,
    SORT_DIRECTION => $sortDirection
];

$gateway = new PuzzleGateway($db);
$puzzles = $gateway->findAll($options);

$totalPuzzles = $gateway->count($options);
$seen = $maxperpage * ($page - 1) + count($puzzles);

$prevLink = $page <= 1 ? "#" : 'userinv.php?' . queryForPage($page - 1);
$nextLink = $totalPuzzles <= $seen ? "#" : 'userinv.php?' . queryForPage($page + 1);
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
                    <th scope="col" class="text-center align-middle" data-sortable="true" data-field="id">Picture</th>
                    <th scope="col" class="col-11 align-middle" data-sortable="true" data-field="brand">Name</th>
                    <th scope="col" class="text-center">Pieces</th>
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
                        <td class='text-center'><button class='btn btn-secondary ' type='submit' data-bs-toggle='modal' data-bs-target='#delete'><i class='bi bi-plus'></td>
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
                    <th scope="col" class="text-center align-middle" data-sortable="true" data-field="id">Picture</th>
                    <th scope="col" class="col-11 align-middle" data-sortable="true" data-field="brand">Name</th>
                    <th scope="col" class="text-center">Pieces</th>
                    <th scope="col" class="text-center">Remove</th>
                </tr>
            </thead>

            <tbody class="table-group-divider">
                <?php foreach ($puzzles as $puzzle) {
                    if (!($puzzle instanceof Puzzle)) continue;
                    echo
                    "<tr class='brand-row'>
                        <th scope='row' class='text-center align-middle id''>" . $puzzle->getPicture() . "</th>
                        <td class='align-middle name'>" . $puzzle->getName() . "</td>
                        <td class='align-middle name'>" . $puzzle->getPieces() . "</td>
                        <td class='text-center'><button class='btn btn-secondary ' type='submit' data-bs-toggle='modal' data-bs-target='#delete'><i class='bi bi-dash'></td>
                    </tr>";
                } ?>
            </tbody>
        </table>
    </div>
</div>