<?php
global $db;
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\object\Status;
use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\UserPuzzle;
use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\object\Puzzle;
use puzzlethings\src\gateway\OwnershipGateway;
use puzzlethings\src\object\Ownership;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Edit User Puzzle';
include 'header.php';
include 'nav.php';

$id = $_GET['id'];

$gateway = new StatusGateway($db);
$statuses = $gateway->findAll();
$gateway = new OwnershipGateway($db);
$ownerships = $gateway->findAll();
$gateway = new PuzzleGateway($db);
$puzzles = $gateway->findAll();

$gateway = new UserPuzzleGateway($db);
$userpuzzle = $gateway->findById($id)

?>

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col-8">
        <form enctype="multipart/form-data" class="align-items-center" action="userinveditc.php" id="useredit" method="post">
            <input type="hidden" tabindex="-1" name="id" value="<?php echo $id ?>">

            <div class="p-2 mb-2 mx-1">
                <label for="puzname" class="form-label"><strong>Puzzle Name</strong></label>
                <input type="text" class="form-control" name="puzname" id="puzname" value="<?php echo $userpuzzle->getPuzzle()->getName(); ?>" disabled>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="pieces" class="form-label"><strong>Piece Count</strong></label>
                <input type="number" class="form-control" name="pieces" id="pieces" min="1" value="<?php echo $userpuzzle->getPuzzle()->getPieces(); ?>" disabled>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="status" class="form-label"><strong>Status</strong></label>
                <div class="">
                    <select class="form-control" name="status" id="status">
                        <?php
                        foreach ($statuses as $status) {
                            if (!($status instanceof Status)) continue;
                            echo
                            "<option " . ($status->getId() === $userpuzzle->getStatus()->getId() ? "selected" : "") . " value='" . $status->getId() . "'>" . $status->getDescription() . "</option>";
                        } ?>
                    </select>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="missingpieces" class="form-label"><strong>Missing Pieces</strong></label>
                <div class="input-group">
                    <input type="number" class="form-control" name="missingpieces" id="missingpieces" min="0" step="1" value="<?php echo $userpuzzle->getMissingPieces(); ?>">
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="start" class="form-label"><strong>Start Date</strong></label>
                <input type="date" class="form-control" name="start" id="start" value="<?php echo date('Y-m-d', strtotime($userpuzzle->getStart())); ?>" disabled>

                <a class="btn btn-secondary my-2" onclick="startDate();">Start Puzzle</a>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="end" class="form-label"><strong>End Date</strong></label>
                <input type="date" class="form-control" name="end" id="end" value="<?php echo date('Y-m-d', strtotime($userpuzzle->getEnd())); ?>" disabled>
                <a class="btn btn-secondary mt-2" onclick="endDate();">Complete Puzzle</a>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="difficulty" class="form-label"><strong>Quality Rating</strong></label>
                <input type="number" class="form-control" name="difficulty" id="difficulty" min="0" max="5" step="1" value="<?php echo $userpuzzle->getDifficulty(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="quality" class="form-label"><strong>Quality Rating</strong></label>
                <input type="number" class="form-control" name="quality" id="quality" min="0" max="5" step="1" value="<?php echo $userpuzzle->getQuality(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="ownership" class="form-label"><strong>Ownership Status</strong></label>
                <div class="">
                    <select class="form-control" name="ownership" id="ownership">
                        <?php
                        foreach ($ownerships as $ownership) {
                            if (!($ownership instanceof Ownership)) continue;
                            echo
                            "<option " . ($ownership->getId() === $userpuzzle->getOwnership()->getId() ? "selected" : "") . " value='" . $ownership->getId() . "'>" . $ownership->getDescription() . "</option>";
                        } ?>
                    </select>
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
                <a class="btn btn-danger" name="cancel" href="userinv.php">Cancel</a>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">
    function startDate() {
        document.getElementById("start").valueAsDate = new Date()
        document.getElementById("start").style.backgroundColor = "red";
    }

    function endDate() {
        document.getElementById("end").valueAsDate = new Date();
        document.getElementById("end").style.backgroundColor = "red";
    }
</script>