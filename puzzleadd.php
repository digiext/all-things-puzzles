<?php
global $db;
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;
use puzzlethings\src\gateway\SourceGateway;
use puzzlethings\src\object\Source;
use puzzlethings\src\gateway\LocationGateway;
use puzzlethings\src\object\Location;
use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\object\Disposition;
use puzzlethings\src\gateway\CategoryGateway;
use puzzlethings\src\object\Category;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Add Puzzle';
include 'header.php';
include 'nav.php';

$gateway = new BrandGateway($db);
$brands = $gateway->findAll();
$gateway = new SourceGateway($db);
$sources = $gateway->findAll();
$gateway = new LocationGateway($db);
$locations = $gateway->findAll();
$gateway = new DispositionGateway($db);
$dispositions = $gateway->findAll();
$gateway = new CategoryGateway($db);
$categories = $gateway->findAll();

?>

<script src="scripts/puzzle_validator.js" data-from="add"></script>
<script src="scripts/puzzle_add.js"></script>

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col-md-8 col-sm-12">
        <form enctype="multipart/form-data" class="align-items-center" action="puzzleaddc.php" method="post" id="form">
            <div class="p-2 mb-2 mx-1" id="dname">
                <label for="puzname" class="form-label"><strong>Puzzle Name</strong></label>
                <input type="text" class="form-control" name="puzname" id="puzname">
                <div id="nameFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1" id="dpieces">
                <label for="pieces" class="form-label"><strong>Piece Count</strong></label>
                <input type="number" class="form-control" name="pieces" id="pieces" min="1">
                <div id="piecesFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1" id="dbrand">
                <label for="brand" class="form-label"><strong>Brand</strong></label>
                <div class="">
                    <select class="form-control" name="brand" id="brand">
                        <?php
                        foreach ($brands as $brand) {
                            if (!($brand instanceof Brand)) continue;
                            echo
                            "<option value='" . $brand->getId() . "'>" . $brand->getName() . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-check my-1">
                    <input type="checkbox" class="form-check-input" name="createNewBrand" id="createNewBrand">
                    <label for="createNewBrand" class="form-check-label">Puzzle brand not listed</label>
                </div>
            </div>

            <div id="newBrandMenu" class="hstack gap-3 p-2 mb-2 mx-2" style="display: none;">
                <div class="vr col-auto"></div>
                <div class="col-12">

                    <div class="p-2 mb-2 mx-1">
                        <label for="brandName" class="form-label"><strong>Brand Name</strong></label>
                        <input type="text" class="form-control" name="brandName" id="brandName">
                    </div>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1" id="dcategory">
                <label for="category" class="form-label"><strong>Category</strong> - Hold Ctrl to select multiple</label>
                <div class="">
                    <select class="form-control" multiple size="5" name="category[]" id="category">
                        <?php
                        foreach ($categories as $category) {
                            if (!($category instanceof Category)) continue;
                            echo
                            "<option value='" . $category->getId() . "'>" . $category->getDescription() . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-check my-1">
                    <input type="checkbox" class="form-check-input" name="createNewCategory" id="createNewCategory">
                    <label for="createNewCategory" class="form-check-label">Category not listed</label>
                </div>
            </div>

            <div id="newCategoryMenu" class="hstack gap-3 p-2 mb-2 mx-2" style="display: none;">
                <div class="vr col-auto"></div>
                <div class="col-12">

                    <div class="p-2 mb-2 mx-1">
                        <label for="categoryDesc" class="form-label"><strong>Categories </strong>(separate by comma)</label>
                        <input type="text" class="form-control" name="categoryDesc" id="categoryDesc">
                    </div>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1" id="dcost">
                <label for="cost" class="form-label"><strong>Cost</strong></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" name="cost" id="cost" min="0" step="0.01">
                    <select class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" name="costCurrency" id="costCurrency">
                        <option value="USD" selected>USD</option>
                        <option value="CAD">CAD</option>
                    </select>
                    <div id="costFeedback"></div>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1" id="dacquired">
                <label for="acquired" class="form-label"><strong>Date Acquired</strong></label>
                <input type="date" class="form-control" name="acquired" id="acquired">
            </div>

            <div class="p-2 mb-2 mx-1" id="dsource">
                <label for="source" class="form-label"><strong>Source</strong></label>
                <div class="">
                    <select class="form-control" name="source" id="source">
                        <?php
                        foreach ($sources as $source) {
                            if (!($source instanceof Source)) continue;
                            echo
                            "<option value='" . $source->getId() . "'>" . $source->getDescription() . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-check my-1">
                    <input type="checkbox" class="form-check-input" name="createNewSource" id="createNewSource">
                    <label for="createNewSource" class="form-check-label">Puzzle source not listed</label>
                </div>
            </div>

            <div id="newSourceMenu" class="hstack gap-3 p-2 mb-2 mx-2" style="display: none;">
                <div class="vr col-auto"></div>
                <div class="col-12">

                    <div class="p-2 mb-2 mx-1">
                        <label for="sourceDesc" class="form-label"><strong>Source Description</strong></label>
                        <input type="text" class="form-control" name="sourceDesc" id="sourceDesc">
                    </div>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="upc" class="form-label"><strong>UPC / ISBN</strong></label>
                <input type="number" class="form-control" name="upc" id="upc" maxlength="13" minlength="12">
                <div id="upcFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="disposition" class="form-label"><strong>Disposition Status</strong></label>
                <div class="">
                    <select class="form-control" name="disposition" id="disposition">
                        <?php
                        foreach ($dispositions as $disposition) {
                            if (!($disposition instanceof Disposition)) continue;
                            echo
                            "<option value='" . $disposition->getId() . "'>" . $disposition->getDescription() . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-check my-1">
                    <input type="checkbox" class="form-check-input" name="createNewDisposition" id="createNewDisposition">
                    <label for="createNewDisposition" class="form-check-label">Puzzle disposition not listed</label>
                </div>
            </div>

            <div id="newDispositionMenu" class="hstack gap-3 p-2 mb-2 mx-2" style="display: none;">
                <div class="vr col-auto"></div>
                <div class="col-12">

                    <div class="p-2 mb-2 mx-1">
                        <label for="dispositionDesc" class="form-label"><strong>Disposition Description</strong></label>
                        <input type="text" class="form-control" name="dispositionDesc" id="dispositionDesc">
                    </div>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="location" class="form-label"><strong>Location Status</strong></label>
                <div class="">
                    <select class="form-control" name="location" id="location">
                        <?php
                        foreach ($locations as $location) {
                            if (!($location instanceof Location)) continue;
                            echo
                            "<option value='" . $location->getId() . "'>" . $location->getDescription() . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-check my-1">
                    <input type="checkbox" class="form-check-input" name="createNewLocation" id="createNewLocation">
                    <label for="createNewLocation" class="form-check-label">Puzzle location not listed</label>
                </div>
            </div>

            <div id="newLocationMenu" class="hstack gap-3 p-2 mb-2 mx-2" style="display: none;">
                <div class="vr col-auto"></div>
                <div class="col-12">

                    <div class="p-2 mb-2 mx-1">
                        <label for="locationDesc" class="form-label"><strong>Location Description</strong></label>
                        <input type="text" class="form-control" name="locationDesc" id="locationDesc">
                    </div>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="picture" class="form-label"><strong>Picture</strong></label>
                <div class="input-group">
                    <input type="file" accept="image/png, image/jpeg" class="form-control" name="picture" id="picture" max="1">
                    <button type="button" class="btn btn-outline-danger" id="pictureclear"><i class="bi bi-trash"></i> Clear</button>
                </div>
            </div>

            <!--            <div class="p-2 mb-2 mx-1">-->
            <!--                <label for="cost" class="form-label"><strong>Cost</strong></label>-->
            <!--                <div class="input-group">-->
            <!--                    <span class="input-group-text">$</span>-->
            <!--                    <input type="number" class="form-control" name="cost" id="cost" min="0" step="0.01">-->
            <!--                    <select class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" name="costCurrency" id="costCurrency">-->
            <!--                        <option value="USD" selected>USD</option>-->
            <!--                        <option value="CAD">CAD</option>-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--            </div>-->

            <div class="p-2 mb-2 mx-1">
                <button type="submit" class="btn btn-primary" name="submit" id="submit" disabled>Submit</button>
                <a class="btn btn-danger" name="cancel" href="home.php">Cancel</a>
            </div>
        </form>
    </div>

    <div class="vr d-none d-sm-block"></div>
    <!-- Preview Card -->
    <div class="card d-none d-sm-block" style="width: 100%">
        <div class="card-header"><strong>Puzzle Listing Preview</strong></div>
        <img src='images/no-image-dark.svg' class='card-img-top object-fit-cover' alt='Puzzle image' id="cardpicture" height="200">
        <div class="card-body placeholder-glow">
            <h5 class="card-title placeholder col-12" id="cardname"></h5>
            <p class="card-subtitle placeholder col-12 text-body-secondary" id="cardbrand"></p>
        </div>
        <ul class="list-group list-group-flush placeholder-glow">
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-puzzle"></i><span id="cardpieces" class="placeholder col-2"></span></li>
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-folder"></i><span id="cardcategory" class="placeholder col-10"></span></li>
            <li class="list-group-item hstack gap-2"><span class="input-group-text py-1">$</span><span id="cardcost" class="placeholder col-1"></span> <span id="cardcurrency">USD</span></li>
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-stars"></i><span id="cardsource" class="placeholder col-3"></span></li>
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-qr-code"></i><span id="cardupc" class="placeholder col-3"></span></li>
        </ul>
    </div>
</div>