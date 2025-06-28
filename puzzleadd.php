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


?>
<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Add Puzzle</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="home.php">Home</a>
    </div>
</div>

<div class="container">
    <h3>Fill in form below for adding a puzzle</h3>
    <form class="row g-3" action="puzzleaddc.php" method="post" name="puzzleadd">
        <div class="col-md-4">
            <label for="puzname" class="form-label">Puzzle Name</label>
            <input type="text" class="form-control" name="puzname" id="puzname">
        </div>

        <div class="col-md-4">
            <label for="pieces" class="form-label">Piece Count</label>
            <input type="number" class="form-control" name="pieces" id="pieces">
        </div>

        <div class="col-md-4">
            <label for="brand" class="form-label">Brand</label>
            <select class="form-select" name="brand" id="brand">
                <?php
                foreach ($brands as $brand) {
                    if (!($brand instanceof Brand)) continue;
                    echo
                    "<option value='" . $brand->getId() . "'>" . $brand->getName() . "</option>";
                } ?>
            </select>
        </div>

        <div class="col-md-3">
            <label for="cost" class="form-label">Cost</label>
            <input type="number" class="form-control" name="cost" id="cost">
        </div>

        <div class="col-md-3">
            <label for="acquired" class="form-label">Date Acquired</label>
            <input type="date" class="form-control" name="acquired" id="acquired">
        </div>

        <div class="col-md-4">
            <label for="source" class="form-label">Source</label>
            <select class="form-select" name="source" id="source">
                <?php
                foreach ($sources as $source) {
                    if (!($source instanceof Source)) continue;
                    echo
                    "<option value='" . $source->getId() . "'>" . $source->getDescription() . "</option>";
                } ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="upc" class="form-label">UPC</label>
            <input type="number" class="form-control" name="upc" id="upc">
        </div>

        <div class="col-md-4">
            <label for="disposition" class="form-label">Disposition Status</label>
            <select class="form-select" name="disposition" id="disposition">
                <?php
                foreach ($dispositions as $disposition) {
                    if (!($disposition instanceof Disposition)) continue;
                    echo
                    "<option value='" . $disposition->getId() . "'>" . $disposition->getDescription() . "</option>";
                } ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="location" class="form-label">Purchase Location</label>
            <select class="form-select" name="location" id="location">
                <?php
                foreach ($locations as $location) {
                    if (!($location instanceof Location)) continue;
                    echo
                    "<option value='" . $location->getId() . "'>" . $location->getDescription() . "</option>";
                } ?>
            </select>
        </div>
        <br>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
        </div>
    </form>
</div>