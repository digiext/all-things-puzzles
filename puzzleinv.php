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

$title = 'Puzzle Inventory';
include 'header.php';
include 'nav.php';

$page = $_GET['page'] ?? 1;
$maxperpage = $_GET['maxperpage'] ?? 8;

$options = [
    PAGE => $page - 1,
    MAX_PER_PAGE => $maxperpage
];

$gateway = new PuzzleGateway($db);
$puzzles = $gateway->findAll($options);

$totalPuzzles = $gateway->count($options);
$seen = $maxperpage * ($page - 1) + count($puzzles);

$query = [];
parse_str($_SERVER['QUERY_STRING'] ?? "", $query);

function queryForPage(int $page): string {
    global $query;
    return http_build_query(array_merge($query, ['page' => $page]));
}

$prevLink = $page <= 1 ? "#" : 'puzzleinv.php?' . queryForPage($page - 1);
$nextLink = $totalPuzzles <= $seen ? "#" : 'puzzleinv.php?' . queryForPage($page + 1);
?>


<div class="container mb-2 mt-4 gap-3 d-flex justify-content-end align-items-center">
    <h3 class="text-center align-text-bottom me-auto">Puzzle Inventory</h3>
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
                <div class='card'>" ?>
            <?php
            if (empty($puzzle->getPicture())) {
                echo "<img src='images/no-image-dark.svg' class='card-img-top object-fit-cover' alt='Placeholder image' height=200>";
            } else {
                echo "<img src='images/uploads/thumbnails/" . $puzzle->getPicture() . "' class='card-img-top mw-100 object-fit-cover' alt='Puzzle image' height=200>";
            } ?>

        <?php echo "<div class='card-body bg-secondary-subtle'>
                        <h5 class='card-title bg-secondary-subtle' id='cardname-" . $puzzle->getId() . "'>" . $puzzle->getName() . "</h5>
                        <p class='card-subtitle text-body-secondary bg-secondary-subtle' id='cardbrand-" . $puzzle->getId() . "'>" . $puzzle->getBrand()->getName() . "</p>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-puzzle'></i><span id='cardpieces-" . $puzzle->getId() . "'>" . $puzzle->getPieces() . "</span></li>
                        <li class='list-group-item hstack gap-2 bg-secondary-subtle'><span class='input-group-text py-1'>$</span><span id='cardcost-" . $puzzle->getId() . "'>" . $puzzle->getCost() . "</span><span id='cardcurrency-" . $puzzle->getId() . "'>USD</span></li>
                        <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-stars'></i><span id='cardsource-" . $puzzle->getId() . "'>" . $puzzle->getSource()->getDescription() . "</span></li>
                        <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-qr-code'></i><span id='cardupc-" . $puzzle->getId() . "'>" . ($puzzle->getUpc() == "" ? "<i class='text-body-secondary'>None</i>" : $puzzle->getUpc()) . "</span></li>
                    </ul>
                    <div class='card-footer bg-secondary-subtle text-center'>
                        <a class='btn btn-primary' href='puzzleedit.php?id=" . $puzzle->getId() . "'>Edit Puzzle</a>
                    </div>
                </div>
            </div>";
        } ?>
    </div>
</div>

<nav aria-label="Puzzle inventory pagination" class="container d-flex align-items-center justify-content-end">
    <ul class="pagination align-middle">
        <li class="page-item">
            <a class="page-link <?php echo $page <= 1 ? 'disabled' : "" ?>" href="puzzleinv.php?<?php echo queryForPage(1) ?>"><i class="bi bi-chevron-double-left"></i></a>
        </li>
        <li class="page-item">
            <a class="page-link <?php echo $page <= 1 ? 'disabled' : "" ?>" href="<?php echo $prevLink?>"><i class="bi bi-chevron-left"></i></a>
        </li>
        <?php
        if ($page == 1) {
            echo "<li class='page-item active'><a class='page-link' href='#'>1</a></li>";

            if ($totalPuzzles > ($maxperpage * ($page))) {
                echo "<li class='page-item'><a class='page-link' href='puzzleinv.php?" . queryForPage($page + 1) . "'>" . $page + 1 . "</a></li>";
            }

            if ($totalPuzzles > ($maxperpage * ($page + 1))) {
                echo "<li class='page-item'><a class='page-link' href='puzzleinv.php?" . queryForPage($page + 2) . "'>" . $page + 2 . "</a></li>";
            }
        } else {
            if ($page >= 3 && !($totalPuzzles > $maxperpage * ($page))) {
                echo "<li class='page-item'><a class='page-link' href='puzzleinv.php?" . queryForPage($page - 2) . "'>" . $page - 2 . "</a></li>";
            }

            echo "<li class='page-item'><a class='page-link' href='puzzleinv.php?" . queryForPage($page - 1) . "'>" . $page - 1 . "</a></li>";
            echo "<li class='page-item active'><a class='page-link' href='#'>" . $page . "</a></li>";

            if ($totalPuzzles > ($maxperpage * ($page))) {
                echo "<li class='page-item'><a class='page-link' href='puzzleinv.php?" . queryForPage($page + 1) . "'>" . $page + 1 . "</a></li>";
            }
        }
        ?>
        <li class="page-item">
            <a class="page-link <?php echo $nextLink === '#' ? 'disabled' : "" ?>" href="<?php echo $nextLink?>"><i class="bi bi-chevron-right"></i></a>
        </li>
    </ul>
</nav>



<!--            --><?php //foreach ($puzzles as $puzzle) {
                    //                if (!($puzzle instanceof Puzzle)) continue;
                    //                echo
                    //                "<tr>
                    //                        <div class='card' style='width: 100%'>
                    //                            <div class='card-header'><strong>Puzzle Listing Preview</strong></div>
                    //                            <div class='card-body placeholder-glow'>
                    //                                <h5 class='card-title col-12' id='cardname'></h5>
                    //                                <p class='card-subtitle placeholder col-12 text-body-secondary' id='cardbrand'></p>
                    //                            </div>
                    //                            <ul class='list-group list-group-flush placeholder-glow'>
                    //                                <li class='list-group-item hstack gap-2'><i class='input-group-text p-2 bi bi-puzzle'></i><span id='cardpieces' class='placeholder col-2'></span></li>
                    //                                <li class='list-group-item hstack gap-2'><span class='input-group-text py-1'>$</span><span id='cardcost' class='placeholder col-1'></span> <span id='cardcurrency'>USD</span></li>
                    //                                <li class='list-group-item hstack gap-2'><i class='input-group-text p-2 bi bi-stars'></i><span id='cardsource' class='placeholder col-3'></span></li>
                    //                                <li class='list-group-item hstack gap-2'><i class='input-group-text p-2 bi bi-qr-code'></i><span id='cardupc' class='placeholder col-3'></span></li>
                    //                            </ul>
                    //                        </div>
                    //                    </tr>";
                    //            } 
                    ?>