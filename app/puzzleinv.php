<?php
global $db;
require_once 'util/function.php';
require_once 'util/constants.php';
require_once 'util/db.php';

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\object\Puzzle;
use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;
use puzzlethings\src\gateway\SourceGateway;
use puzzlethings\src\object\Source;
use puzzlethings\src\gateway\LocationGateway;
use puzzlethings\src\object\Location;
use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\object\Disposition;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$gateway = new BrandGateway($db);
$brands = $gateway->findAll();
$gateway = new SourceGateway($db);
$sources = $gateway->findAll();
$gateway = new LocationGateway($db);
$locations = $gateway->findAll();
$gateway = new DispositionGateway($db);
$dispositions = $gateway->findAll();

$title = 'Puzzle Inventory';
include 'header.php';
include 'nav.php';

$page = $_GET['page'] ?? 1;
$maxperpage = $_GET['maxperpage'] ?? 8;
$sort = $_GET['sort'] ?? PUZ_ID;
$sortDirection = $_GET['sort_direction'] ?? SQL_SORT_ASC;
$filters = $_GET['filters'] ?? [];

$filtersFinal = [];

$query = [];
parse_str($_SERVER['QUERY_STRING'] ?? "", $query);

foreach ($query as $k => $v) {
    if (!in_array($k, PUZ_FILTERS)) continue;

    $explodedv = explode(',', $v);
    if (count($explodedv) == 1) {
        $filtersFinal = array_merge($filtersFinal, [
            $k => $explodedv[0]
        ]);
    } else if (count($explodedv) == 2) {
        $filtersFinal = array_merge($filtersFinal, [
            $k => [$explodedv[0], $explodedv[1]]
        ]);
    }
}

//var_dump($filtersFinal);

function queryForPage(int $page, array $extras = []): string
{
    global $query;
    return http_build_query(array_merge($query, ['page' => $page], $extras));
}

$options = [
    PAGE => $page - 1,
    MAX_PER_PAGE => $maxperpage,
    SORT => $sort,
    SORT_DIRECTION => $sortDirection,
    FILTERS => $filtersFinal,
];

$gateway = new PuzzleGateway($db);
$puzzles = $gateway->findAll($options);

$totalPuzzles = $gateway->count($options);
$seen = $maxperpage * ($page - 1) + count($puzzles);

$prevLink = $page <= 1 ? "#" : 'puzzleinv.php?' . queryForPage($page - 1);
$nextLink = $totalPuzzles <= $seen ? "#" : 'puzzleinv.php?' . queryForPage($page + 1);
?>

<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.min.css">
<script src="scripts/puzzles.js"></script>
<script src="scripts/bootstrap-slider.min.js"></script>

<div class="container mb-2 mt-4 gap-3 d-flex justify-content-end align-items-center">
    <h3 class="text-center align-text-bottom me-auto">Puzzle Inventory</h3>
    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filters">Filters</button>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Sort</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="puzzleinv.php?<?php echo queryForPage(1, [SORT => PUZ_ID]) ?>">Default Sort</a></li>
            <li><a class="dropdown-item" href="puzzleinv.php?<?php echo queryForPage(1, [SORT => PUZ_NAME]) ?>">Puzzle Name</a></li>
            <li><a class="dropdown-item" href="puzzleinv.php?<?php echo queryForPage(1, [SORT => PUZ_PIECES]) ?>">Puzzle Pieces</a></li>
            <li><a class="dropdown-item" href="puzzleinv.php?<?php echo queryForPage(1, [SORT => PUZ_SORT_BRAND_NAME]) ?>">Puzzle Brands</a></li>
            <li><a class="dropdown-item" href="puzzleinv.php?<?php echo queryForPage(1, [SORT => PUZ_COST]) ?>">Puzzle Cost</a></li>
            <li><a class="dropdown-item" href="puzzleinv.php?<?php echo queryForPage(1, [SORT => PUZ_UPC]) ?>">Puzzle UPC</a></li>
        </ul>
    </div>
    <div>
        <a class="btn btn-primary" href="home.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>

<div class="container my-2">
    <div class="row g-3">
        <?php
        foreach ($puzzles as $puzzle) {
            if (!($puzzle instanceof Puzzle)) continue;
            $puzcatnames = $gateway->findCatNames($puzzle->getId()) ?? [];
            $cattxt = join(", ", $puzcatnames);

            echo
            "<div class='col-md-3 col-sm-12'>
                <div class='card h-100' data-id='" . $puzzle->getId() . "' data-name='" . $puzzle->getName() . "'>";

            if (empty($puzzle->getPicture())) {
                echo "<img src='images/no-image-dark.svg' class='card-img-top object-fit-cover' alt='Placeholder image' height=200>";
            } else {
                echo "<img src='" . getThumbnail($puzzle->getPicture()) . "' class='card-img-top mw-100 object-fit-cover' alt='Puzzle image' height=200>";
            }

            echo " 
                <div class='card-body bg-secondary-subtle'>
                    <h5 class='card-title bg-secondary-subtle name' id='cardname-" . $puzzle->getId() . "'>" . $puzzle->getName() . "</h5>
                    <p class='card-subtitle text-body-secondary bg-secondary-subtle' id='cardbrand-" . $puzzle->getId() . "'>" . $puzzle->getBrand()->getName() . "</p>
                </div>
                <ul class='list-group list-group-flush'>
                    <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-puzzle'></i><span id='cardpieces-" . $puzzle->getId() . "'>" . $puzzle->getPieces() . "</span></li>
                    <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-folder'></i><span id='cardcategory-" . $puzzle->getId() . "' class='col-10'>" . ($cattxt === '' ? "<i class='text-body-secondary'>None</i>" : $cattxt) . "</span></li>
                    <li class='list-group-item hstack gap-2 bg-secondary-subtle'><span class='input-group-text py-1'>$</span><span id='cardcost-" . $puzzle->getId() . "'>" . $puzzle->getCost() . "</span><span id='cardcurrency-" . $puzzle->getId() . "'>USD</span></li>
                    <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-stars'></i><span id='cardsource-" . $puzzle->getId() . "'>" . $puzzle->getSource()->getDescription() . "</span></li>
                    <li class='list-group-item hstack gap-2 bg-secondary-subtle'><i class='input-group-text p-2 bi bi-qr-code'></i><span id='cardupc-" . $puzzle->getId() . "'>" . ($puzzle->getUpc() == "" ? "<i class='text-body-secondary'>None</i>" : $puzzle->getUpc()) . "</span></li>
                </ul>
                <div class='card-footer bg-secondary-subtle text-center'>
                    <a class='btn btn-primary me-2' href='puzzleedit.php?id=" . $puzzle->getId() . "'>Edit Puzzle</a>
                    <button class='btn btn-danger delete' type='submit' data-bs-toggle='modal' data-bs-target='#delete'><i class='bi bi-trash'></i> Delete Puzzle</td>
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
            <a class="page-link <?php echo $page <= 1 ? 'disabled' : "" ?>" href="<?php echo $prevLink ?>"><i class="bi bi-chevron-left"></i></a>
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
            <a class="page-link <?php echo $nextLink === '#' ? 'disabled' : "" ?>" href="<?php echo $nextLink ?>"><i class="bi bi-chevron-right"></i></a>
        </li>
    </ul>
</nav>

<!-- Filter Modal -->
<div class="modal fade" id="filters" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterLabel">Filters</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="column" action="puzzleinv.php" method="get">
                <div class="modal-body">
                    <div class="col-12 m-2">
                        <label for="filtname"><strong>Name</strong></label>
                        <input type="text" class="form-control" id="filtname">
                    </div>

                    <label class="ms-2" for="filtpieces"><strong>Pieces</strong></label>
                    <div class="hstack m-2 g-3 align-items-center justify-content-between">

                        <div class="col-3">
                            <input type="number" class="form-control" id="filtpiecemin" value="0" aria-label="Minimum pieces">
                        </div>
                        <input
                            id="piecesSliderBase"
                            name="<?php echo PUZ_FILTER_PIECES ?>"
                            type="text"
                            data-provide="slider"
                            data-slider-id="piecesSlider"
                            data-slider-min="0"
                            data-slider-max="5000"
                            data-slider-step="100"
                            data-slider-value="[0,5000]"
                            data-slider-tooltip="hide"
                            value="0,5000">
                        <div class="col-3">
                            <input type="number" class="form-control col" id="filtpiecemax" value="5000" aria-label="Maximum pieces">
                        </div>
                    </div>
                    <div class="col-12 m-2">
                        <label for="brand" class="form-label"><strong>Brand</strong></label>
                        <div class="">
                            <select class="form-control" name="<?php echo PUZ_FILTER_BRAND ?>" id="filtbrand">
                                <?php
                                echo "<option hidden disabled selected value> -- select an option -- </option>";
                                foreach ($brands as $brand) {
                                    if (!($brand instanceof Brand)) continue;
                                    echo
                                    "<option value='" . $brand->getId() . "'>" . $brand->getName() . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <label class="ms-2" for="filtpieces"><strong>Cost</strong></label>
                    <div class="hstack m-2 g-3 align-items-center justify-content-between">

                        <div class="col-3">
                            <input type="number" class="form-control" id="filtcostmin" value="0" aria-label="Minimum Cost">
                        </div>
                        <input
                            id="costSlider"
                            name="<?php echo PUZ_FILTER_COST ?>"
                            type="text"
                            data-provide="slider"
                            data-slider-id="costSlider"
                            data-slider-min="0"
                            data-slider-max="100"
                            data-slider-step="1"
                            data-slider-value="[0,100]"
                            data-slider-tooltip="hide"
                            value="0,100">
                        <div class="col-3">
                            <input type="number" class="form-control col" id="filtcostmax" value="100" aria-label="Maximum Cost">
                        </div>
                    </div>
                    <div class="col-12 m-2">
                        <label for="source" class="form-label"><strong>Source</strong></label>
                        <div class="">
                            <select class="form-control" name="<?php echo PUZ_FILTER_SOURCE ?>" id="filtsource">
                                <?php
                                echo "<option hidden disabled selected value> -- select an option -- </option>";
                                foreach ($sources as $source) {
                                    if (!($source instanceof Source)) continue;
                                    echo
                                    "<option value='" . $source->getId() . "'>" . $source->getDescription() . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 m-2">
                        <label for="disposition" class="form-label"><strong>Disposition</strong></label>
                        <div class="">
                            <select class="form-control" name="<?php echo PUZ_FILTER_DISPOSITION ?>" id="filtdisp">
                                <?php
                                echo "<option hidden disabled selected value> -- select an option -- </option>";
                                foreach ($dispositions as $disposition) {
                                    if (!($disposition instanceof Disposition)) continue;
                                    echo
                                    "<option value='" . $disposition->getId() . "'>" . $disposition->getDescription() . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 m-2">
                        <label for="location" class="form-label"><strong>Location</strong></label>
                        <div class="">
                            <select class="form-control" name="<?php echo PUZ_FILTER_LOCATION ?>" id="filtlocation">
                                <?php
                                echo "<option hidden disabled selected value> -- select an option -- </option>";
                                foreach ($locations as $location) {
                                    if (!($location instanceof Location)) continue;
                                    echo
                                    "<option value='" . $location->getId() . "'>" . $location->getDescription() . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteLabel">Delete Confirmation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="column" action="puzzledelete.php" method="post" name="login">
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">Are you <strong>sure</strong> you want to delete this puzzle?</div>
                    <div class="col-auto">
                        <label for="deleteId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control id" id="deleteId" name="id" value="<?php echo $puzzles[0]->getId() ?? 0; ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="deletePuzzle" class="col-form-label">Puzzle</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deletePuzzle" name="puzzle" value="<?php echo $puzzles[0]->getName() ?? 'null'; ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success" name="submit">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let piecesSlider = new Slider("#piecesSlider");
    piecesSlider.on("slide", function(sliderValue) {
        $("#filtpiecemin").value = sliderValue[0];
        $("#filtpiecemax").value = sliderValue[1];
    });

    let costSlider = new Slider("#costSlider");
    costSlider.on("slide", function(sliderValue) {
        document.getElementById("filtcostmin").value = sliderValue[0];
        document.getElementById("filtcostmax").value = sliderValue[1];
    });
</script>

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