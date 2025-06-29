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

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col-8">
        <form class="align-items-center" action="puzzleaddc.php" method="post">
            <div class="p-2 mb-2 mx-1">
                <label for="puzname" class="form-label"><strong>Puzzle Name</strong></label>
                <input type="text" class="form-control" name="puzname" id="puzname">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="pieces" class="form-label"><strong>Piece Count</strong></label>
                <input type="number" class="form-control" name="pieces" id="pieces" min="1">
            </div>

            <div class="p-2 mb-2 mx-1">
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

            <div class="p-2 mb-2 mx-1">
                <label for="cost" class="form-label"><strong>Cost</strong></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" name="cost" id="cost" min="0" step="0.01">
                    <select class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" name="costCurrency" id="costCurrency">
                        <option value="USD" selected>USD</option>
                        <option value="CAD">CAD</option>
                    </select>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="acquired" class="form-label"><strong>Date Acquired</strong></label>
                <input type="date" class="form-control" name="acquired" id="acquired">
            </div>

            <div class="p-2 mb-2 mx-1">
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
                <label for="upc" class="form-label"><strong>UPC</strong></label>
                <input type="number" class="form-control" name="upc" id="upc" maxlength="12" minlength="12">
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
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a class="btn btn-danger" name="cancel" href="home.php">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div class="vr"></div>
<!-- Preview Card -->
<div class="card" style="width: 100%">
    <div class="card-header"><strong>Puzzle Listing Preview</strong></div>
    <div class="card-body placeholder-glow">
        <h5 class="card-title placeholder col-12" id="cardname"></h5>
        <p class="card-subtitle placeholder col-12 text-body-secondary" id="cardbrand"></p>
    </div>
    <ul class="list-group list-group-flush placeholder-glow">
        <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-puzzle"></i><span id="cardpieces" class="placeholder col-2"></span></li>
        <li class="list-group-item hstack gap-2"><span class="input-group-text py-1">$</span><span id="cardcost" class="placeholder col-1"></span> <span id="cardcurrency">USD</span></li>
        <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-stars"></i><span id="cardsource" class="placeholder col-3"></span></li>
        <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-qr-code"></i><span id="cardupc" class="placeholder col-3"></span></li>
    </ul>
</div>
</div>

<script>
    $(function() {
        let puzzleName = $('#puzname');
        let cardName = $('#cardname');
        let puzzlePieces = $('#pieces');
        let cardPieces = $('#cardpieces');
        let puzzleBrand = $('#brand');
        let newBrand = $('#brandName');
        let cardBrand = $('#cardbrand')
        let puzzleCost = $('#cost');
        let cardCost = $('#cardcost');
        let puzzleCostCurrency = $('#costCurrency')
        let cardCurrency = $('#cardcurrency');
        let puzzleSource = $('#source');
        let newSource = $('#sourceDesc')
        let cardSource = $('#cardsource');
        let puzzleUpc = $('#upc');
        let cardUpc = $('#cardupc');

        let brandCheckbox = $('#createNewBrand');
        let brandDiv = $('#newBrandMenu');
        let sourceCheckbox = $('#createNewSource');
        let sourceDiv = $('#newSourceMenu');
        let dispositionCheckbox = $('#createNewDisposition');
        let dispositionDiv = $('#newDispositionMenu');
        let locationCheckbox = $('#createNewLocation');
        let locationDiv = $('#newLocationMenu');

        puzzleName.on('keyup', function() {
            if (puzzleName.val() !== '') {
                cardName.removeClass('placeholder col-12');
                cardName.text(puzzleName.val());
            } else {
                cardName.addClass('placeholder col-12');
                cardName.text('');
            }
        })

        puzzlePieces.on('keyup', function() {
            if (puzzlePieces.val() !== '') {
                cardPieces.removeClass('placeholder col-2');
                cardPieces.text(puzzlePieces.val());
            } else {
                cardPieces.addClass('placeholder col-2');
                cardPieces.text('');
            }
        })

        puzzleBrand.on('change', function() {
            cardBrand.removeClass('placeholder col-12');
            cardBrand.text($(this).find('option:selected').text());
        })

        newBrand.on('keyup', function() {
            if (brandCheckbox.prop('checked') === true) {
                cardBrand.removeClass('placeholder col-12');
                cardBrand.text(newBrand.val());
            }
        })

        puzzleCost.on('keyup', function() {
            if (puzzleCost.val() !== '') {
                cardCost.removeClass('placeholder col-1');
                cardCost.text(puzzleCost.val());
            } else {
                cardCost.addClass('placeholder col-1');
                cardCost.text('');
            }
        })

        puzzleCostCurrency.on('change', function() {
            cardCurrency.text($(this).find('option:selected').text());
        })

        puzzleSource.on('change', function() {
            cardSource.removeClass('placeholder col-3');
            cardSource.text($(this).find('option:selected').text());
        })

        newSource.on('keyup', function() {
            if (sourceCheckbox.prop('checked') === true) {
                cardSource.removeClass('placeholder col-3');
                cardSource.text(newSource.val());
            }
        })

        puzzleUpc.on('keyup', function() {
            if (puzzleUpc.val() !== '') {
                cardUpc.removeClass('placeholder col-3');
                cardUpc.text(puzzleUpc.val());
            } else {
                cardUpc.addClass('placeholder col-3');
                cardUpc.text('');
            }
        })

        brandCheckbox.on('change', function() {
            if (brandCheckbox.prop('checked') === true) {
                brandDiv.show(200);
                if (newBrand.val() !== '') {
                    cardBrand.removeClass('placeholder col-12');
                    cardBrand.text(newBrand.val());
                } else {
                    cardBrand.addClass('placeholder col-12');
                    cardBrand.text('');
                }
            } else {
                brandDiv.hide(200);
                cardBrand.removeClass('placeholder col-12');
                cardBrand.text(puzzleBrand.find('option:selected').text());
            }
        })

        sourceCheckbox.on('change', function() {
            if (sourceCheckbox.prop('checked') === true) {
                sourceDiv.show(200);
                if (newSource.val() !== '') {
                    cardSource.removeClass('placeholder col-3');
                    cardSource.text(newSource.val());
                } else {
                    cardSource.addClass('placeholder col-3');
                    cardSource.text('');
                }
            } else {
                sourceDiv.hide(200);
                cardSource.removeClass('placeholder col-3');
                cardSource.text(puzzleSource.find('option:selected').text());
            }
        })

        dispositionCheckbox.on('change', function() {
            if (dispositionCheckbox.prop('checked') === true) {
                dispositionDiv.show(200);
            } else {
                dispositionDiv.hide(200);
            }
        })

        locationCheckbox.on('change', function() {
            if (locationCheckbox.prop('checked') === true) {
                locationDiv.show(200);
            } else {
                locationDiv.hide(200);
            }
        })
    })
</script>