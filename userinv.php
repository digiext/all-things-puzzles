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
$maxperpage = $_GET['maxperpage'] ?? 4;
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
    <h3 class="text-center align-text-bottom me-auto">User Inventory</h3>

    <div>
        <a class="btn btn-primary" href="home.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>

<div class="container my-2">
    <div class="row row-cols-4 g-3">
        <?php
        foreach ($puzzles as $puzzle) {
            if (!($puzzle instanceof Puzzle)) continue;
            echo
            "<div class='col'>
                <div class='card h-100' data-id='" . $puzzle->getId() . "' data-name='" . $puzzle->getName() . "'>" ?>
            <?php
            if (empty($puzzle->getPicture())) {
                echo "<img src='images/no-image-dark.svg' class='card-img-top object-fit-cover' alt='Placeholder image' height=200>";
            } else {
                echo "<img src='images/uploads/thumbnails/" . $puzzle->getPicture() . "' class='card-img-top mw-100 object-fit-cover' alt='Puzzle image' height=200>";
            } ?>

        <?php echo "<div class='card-body bg-secondary-subtle'>
                        <h5 class='card-title bg-secondary-subtle name' id='cardname-" . $puzzle->getId() . "'>" . $puzzle->getName() . "</h5>
                        <p class='card-subtitle text-body-secondary bg-secondary-subtle' id='cardbrand-" . $puzzle->getId() . "'>" . $puzzle->getBrand()->getName() . "</p>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-puzzle'></i><span id='cardpieces-" . $puzzle->getId() . "'>" . $puzzle->getPieces() . "</span></li>
                    </ul>
                    <div class='card-footer bg-secondary-subtle text-center'>
                        <a class='btn btn-primary me-2' href='puzzleedit.php?id=" . $puzzle->getId() . "'>Edit Puzzle</a>
                    </div>
                </div>
            </div>";
        } ?>
    </div>
</div>

<nav aria-label="Puzzle inventory pagination" class="container d-flex align-items-center justify-content-end">
    <ul class="pagination align-middle">
        <li class="page-item">
            <a class="page-link <?php echo $page <= 1 ? 'disabled' : "" ?>" href="userinv.php?<?php echo queryForPage(1) ?>"><i class="bi bi-chevron-double-left"></i></a>
        </li>
        <li class="page-item">
            <a class="page-link <?php echo $page <= 1 ? 'disabled' : "" ?>" href="<?php echo $prevLink ?>"><i class="bi bi-chevron-left"></i></a>
        </li>
        <?php
        if ($page == 1) {
            echo "<li class='page-item active'><a class='page-link' href='#'>1</a></li>";

            if ($totalPuzzles > ($maxperpage * ($page))) {
                echo "<li class='page-item'><a class='page-link' href='userinv.php?" . queryForPage($page + 1) . "'>" . $page + 1 . "</a></li>";
            }

            if ($totalPuzzles > ($maxperpage * ($page + 1))) {
                echo "<li class='page-item'><a class='page-link' href='userinv.php?" . queryForPage($page + 2) . "'>" . $page + 2 . "</a></li>";
            }
        } else {
            if ($page >= 3 && !($totalPuzzles > $maxperpage * ($page))) {
                echo "<li class='page-item'><a class='page-link' href='userinv.php?" . queryForPage($page - 2) . "'>" . $page - 2 . "</a></li>";
            }

            echo "<li class='page-item'><a class='page-link' href='userinv.php?" . queryForPage($page - 1) . "'>" . $page - 1 . "</a></li>";
            echo "<li class='page-item active'><a class='page-link' href='#'>" . $page . "</a></li>";

            if ($totalPuzzles > ($maxperpage * ($page))) {
                echo "<li class='page-item'><a class='page-link' href='userinv.php?" . queryForPage($page + 1) . "'>" . $page + 1 . "</a></li>";
            }
        }
        ?>
        <li class="page-item">
            <a class="page-link <?php echo $nextLink === '#' ? 'disabled' : "" ?>" href="<?php echo $nextLink ?>"><i class="bi bi-chevron-right"></i></a>
        </li>
    </ul>
</nav>