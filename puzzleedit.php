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
use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\object\Disposition;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Edit Puzzle';
include 'header.php';
include 'nav.php';

$id = $_GET['id'];

$gateway = new BrandGateway($db);
$brands = $gateway->findAll();
$gateway = new SourceGateway($db);
$sources = $gateway->findAll();
$gateway = new LocationGateway($db);
$locations = $gateway->findAll();
$gateway = new DispositionGateway($db);
$dispositions = $gateway->findAll();

$gateway = new PuzzleGateway($db);
$puzzle = $gateway->findById($id)

?>

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col-8">
        <form enctype="multipart/form-data" class="align-items-center" action="puzzleeditc.php" method="post">
            <input type="hidden" tabindex="-1" name="id" value="<?php echo $id ?>">
            <input type="hidden" tabindex="-1" name="oldpicture" id="currpicture" value="<?php echo $puzzle->getPicture() ?>">
            <input type="hidden" tabindex="-1" name="deleteoldpic" id="deleteoldpic" value="false">

            <div class="p-2 mb-2 mx-1">
                <label for="puzname" class="form-label"><strong>Puzzle Name</strong></label>
                <input type="text" class="form-control" name="puzname" id="puzname" value="<?php echo $puzzle->getName(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="pieces" class="form-label"><strong>Piece Count</strong></label>
                <input type="number" class="form-control" name="pieces" id="pieces" min="1" value="<?php echo $puzzle->getPieces(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="brand" class="form-label"><strong>Brand</strong></label>
                <div class="">
                    <select class="form-control" name="brand" id="brand">
                        <?php
                        foreach ($brands as $brand) {
                            if (!($brand instanceof Brand)) continue;
                            echo
                            "<option " . ($brand->getId() === $puzzle->getBrand()->getId() ? "selected" : "") . " value='" . $brand->getId() . "'>" . $brand->getName() . "</option>";
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
                    <input type="number" class="form-control" name="cost" id="cost" min="0" step="0.01" value="<?php echo $puzzle->getCost(); ?>">
                    <select class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" name="costCurrency" id="costCurrency">
                        <option value="USD" selected>USD</option>
                        <option value="CAD">CAD</option>
                    </select>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="acquired" class="form-label"><strong>Date Acquired</strong></label>
                <input type="date" class="form-control" name="acquired" id="acquired" value="<?php echo date('Y-m-d', strtotime($puzzle->getAcquired())); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="source" class="form-label"><strong>Source</strong></label>
                <div class="">
                    <select class="form-control" name="source" id="source">
                        <?php
                        foreach ($sources as $source) {
                            if (!($source instanceof Source)) continue;
                            echo
                            "<option " . ($source->getId() === $puzzle->getSource()->getId() ? "selected" : "") . " value='" . $source->getId() . "'>" . $source->getDescription() . "</option>";
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
                <input type="number" class="form-control" name="upc" id="upc" maxlength="12" minlength="12" value="<?php echo $puzzle->getUpc(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="disposition" class="form-label"><strong>Disposition Status</strong></label>
                <div class="">
                    <select class="form-control" name="disposition" id="disposition">
                        <?php
                        foreach ($dispositions as $disposition) {
                            if (!($disposition instanceof Disposition)) continue;
                            echo
                            "<option " . ($disposition->getId() === $puzzle->getDisposition()->getId() ? "selected" : "") . " value='" . $disposition->getId() . "'>" . $disposition->getDescription() . "</option>";
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
                            "<option " . ($location->getId() === $puzzle->getLocation()->getId() ? "selected" : "") . " value='" . $location->getId() . "'>" . $location->getDescription() . "</option>";
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
                    <input type="file" accept="image/png, image/jpeg" class="form-control" name="picture" id="picture" max="1" value="<?php echo $puzzle->getPicture(); ?>">
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
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a class="btn btn-danger" name="cancel" href="puzzleinv.php">Cancel</a>
            </div>
        </form>
    </div>

    <div class="vr"></div>
    <!-- Preview Card -->
    <div class="card" style="width: 100%">
        <div class="card-header"><strong>Puzzle Listing Preview</strong></div>
        <div class="card-img-top position-relative">
            <img src='<?php echo ($puzzle->getPicture() ?? '') === '' ? 'images/no-image-dark.svg' : 'images/uploads/thumbnails/' . $puzzle->getPicture() ?>' class='object-fit-cover w-100' alt='Puzzle image' id="cardpicture" height="200">
            <button class="position-absolute top-0 start-100 translate-middle badge border rounded-3 bg-danger p-2" id="deleteImageButton"><i class="bi bi-trash"></i></button>
        </div>
        <div class="card-body">
            <h5 class="card-title" id="cardname"><?php echo $puzzle->getName() ?></h5>
            <p class="card-subtitle text-body-secondary" id="cardbrand"><?php echo $puzzle->getBrand()->getName() ?></p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-puzzle"></i><span id="cardpieces"><?php echo $puzzle->getPieces() ?></span></li>
            <li class="list-group-item hstack gap-2"><span class="input-group-text py-1">$</span><span id="cardcost"><?php echo $puzzle->getCost() ?></span><span id="cardcurrency">USD</span></li>
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-stars"></i><span id="cardsource"><?php echo $puzzle->getSource()->getDescription() ?></span></li>
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-qr-code"></i><span id="cardupc"><?php echo $puzzle->getUpc() == "" ? "<i class='text-body-secondary'>None</i>" : $puzzle->getUpc() ?></span></li>
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
        let picture = $('#picture');
        let pictureClear = $('#pictureclear');
        let pictureDelete = $('#deleteImageButton');
        let cardPicture = $('#cardpicture')
        let currpicture = $('#currpicture');
        let deleteoldpic = $('#deleteoldpic');

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
                cardName.text(puzzleName.val());
            } else {
                cardName.text('');
            }
        })

        puzzlePieces.on('keyup', function() {
            if (puzzlePieces.val() !== '') {
                cardPieces.text(puzzlePieces.val());
            } else {
                cardPieces.text('');
            }
        })

        puzzleBrand.on('change', function() {
            cardBrand.text($(this).find('option:selected').text());
        })

        newBrand.on('keyup', function() {
            if (brandCheckbox.prop('checked') === true) {
                cardBrand.text(newBrand.val());
            }
        })

        puzzleCost.on('keyup', function() {
            if (puzzleCost.val() !== '') {
                cardCost.text(puzzleCost.val());
            } else {
                cardCost.text('');
            }
        })

        puzzleCostCurrency.on('change', function() {
            cardCurrency.text($(this).find('option:selected').text());
        })

        puzzleSource.on('change', function() {
            cardSource.text($(this).find('option:selected').text());
        })

        newSource.on('keyup', function() {
            if (sourceCheckbox.prop('checked') === true) {
                cardSource.text(newSource.val());
            }
        })

        puzzleUpc.on('keyup', function() {
            if (puzzleUpc.val() !== '') {
                cardUpc.text(puzzleUpc.val());
            } else {
                cardUpc.html("<i class='text-body-secondary'>None</i>");
            }
        })

        brandCheckbox.on('change', function() {
            if (brandCheckbox.prop('checked') === true) {
                brandDiv.show(200);
                if (newBrand.val() !== '') {
                    cardBrand.text(newBrand.val());
                } else {
                    cardBrand.text('');
                }
            } else {
                brandDiv.hide(200);
                cardBrand.text(puzzleBrand.find('option:selected').text());
            }
        })

        sourceCheckbox.on('change', function() {
            if (sourceCheckbox.prop('checked') === true) {
                sourceDiv.show(200);
                if (newSource.val() !== '') {
                    cardSource.text(newSource.val());
                } else {
                    cardSource.text('');
                }
            } else {
                sourceDiv.hide(200);
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

        picture.on('change', function() {
            if (this.files && this.files[0]) {
                let file = this.files[0];
                let reader = new FileReader();

                reader.onload = function(e) {
                    cardPicture.attr('src', e.target.result)
                }

                reader.readAsDataURL(file);
            } else {
                cardPicture.attr('src', '/images/no-image-dark.svg');
            }
        })

        pictureClear.on('click', function() {
            picture.val(null);
            cardPicture.attr('src', '/images/uploads/thumbnails/' + currpicture.val());
        })

        pictureDelete.on('click', function() {
            picture.val(null);
            cardPicture.attr('src', '/images/no-image-dark.svg');
            deleteoldpic.val("true");
        })
    })
</script>