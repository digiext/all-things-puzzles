<?php
global $db;
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;


//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Add Wishlist Puzzle';
include 'header.php';
include 'nav.php';

$gateway = new BrandGateway($db);
$brands = $gateway->findAll();

?>

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col-md-8 col-sm-12">
        <form enctype="multipart/form-data" class="align-items-center" action="puzzlewishaddc.php" method="post">
            <input type="hidden" tabindex="-1" name="userid" value="<?php echo getUserId() ?>">
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
                <label for="upc" class="form-label"><strong>UPC</strong></label>
                <input type="number" class="form-control" name="upc" id="upc" maxlength="12" minlength="12">
            </div>

            <div class="p-2 mb-2 mx-1">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a class="btn btn-danger" name="cancel" href="home.php">Cancel</a>
            </div>
        </form>
    </div>

    <div class="vr d-none d-sm-block"></div>
    <!-- Preview Card -->
    <div class="card d-none d-sm-block" style="width: 100%">
        <div class="card-header"><strong>Puzzle Listing Preview</strong></div>
        <div class="card-body placeholder-glow">
            <h5 class="card-title placeholder col-12" id="cardname"></h5>
            <p class="card-subtitle placeholder col-12 text-body-secondary" id="cardbrand"></p>
        </div>
        <ul class="list-group list-group-flush placeholder-glow">
            <li class="list-group-item hstack gap-2"><i class="input-group-text p-2 bi bi-puzzle"></i><span id="cardpieces" class="placeholder col-2"></span></li>
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
        let puzzleUpc = $('#upc');
        let cardUpc = $('#cardupc');

        let brandCheckbox = $('#createNewBrand');
        let brandDiv = $('#newBrandMenu');

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

    })
</script>