<?php
global $db;
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;
use puzzlethings\src\gateway\PuzzleWishGateway;


//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Edit Wishlist Puzzle';
include 'header.php';
include 'nav.php';

$id = $_GET['id'];

$gateway = new BrandGateway($db);
$brands = $gateway->findAll();

$gateway = new PuzzleWishGateway($db);
$puzzlewish = $gateway->findById($id)

?>

<script src="scripts/puzzle_validator.js"></script>

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col-md-8 col-sm-12">
        <form enctype="multipart/form-data" class="align-items-center" action="puzzlewisheditc.php" method="post">
            <input type="hidden" tabindex="-1" name="id" value="<?php echo $id ?>">

            <div class="p-2 mb-2 mx-1">
                <label for="puzname" class="form-label"><strong>Puzzle Name</strong></label>
                <input type="text" class="form-control" name="puzname" id="puzname" value="<?php echo $puzzlewish->getName(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="pieces" class="form-label"><strong>Piece Count</strong></label>
                <input type="number" class="form-control" name="pieces" id="pieces" min="1" value="<?php echo $puzzlewish->getPieces(); ?>">
                <div id="piecesFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="brand" class="form-label"><strong>Brand</strong></label>
                <div class="">
                    <select class="form-control" name="brand" id="brand">
                        <?php
                        foreach ($brands as $brand) {
                            if (!($brand instanceof Brand)) continue;
                            echo
                            "<option " . ($brand->getId() === $puzzlewish->getBrand()->getId() ? "selected" : "") . " value='" . $brand->getId() . "'>" . $brand->getName() . "</option>";
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
                <label for="upc" class="form-label"><strong>UPC / ISBN</strong></label>
                <input type="number" class="form-control" name="upc" id="upc" maxlength="12" minlength="12" value="<?php echo $puzzlewish->getUpc(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a class="btn btn-danger" name="cancel" href="puzzlewish.php">Cancel</a>
            </div>
        </form>
    </div>

    <div class="vr d-none d-sm-block"></div>
    <!-- Preview Card -->
    <div class="card d-none d-sm-block" style="width: 100%">
        <div class="card-header"><strong>Puzzle Listing Preview</strong></div>
        <div class="card-img-top position-relative">
        </div>
        <div class="card-body">
            <h5 class="card-title" id="cardname"><?php echo $puzzlewish->getName() ?></h5>
            <p class="card-subtitle text-body-secondary" id="cardbrand"><?php echo $puzzlewish->getBrand()->getName() ?></p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-puzzle"></i><span id="cardpieces"><?php echo $puzzlewish->getPieces() ?></span></li>
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-qr-code"></i><span id="cardupc"><?php echo $puzzlewish->getUpc() == "" ? "<i class='text-body-secondary'>None</i>" : $puzzlewish->getUpc() ?></span></li>
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
        let puzzleUpc = $('#upc');
        let cardUpc = $('#cardupc');

        let brandCheckbox = $('#createNewBrand');
        let brandDiv = $('#newBrandMenu');

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

    })
</script>