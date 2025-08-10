<?php
global $db;
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\object\Status;
use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\gateway\PuzzleGateway;
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
$complete = $gateway->findByName("Completed");
$inProgress = $gateway->findByName("In Progress");
$gateway = new OwnershipGateway($db);
$ownerships = $gateway->findAll();
$gateway = new PuzzleGateway($db);
$puzzles = $gateway->findAll();

$gateway = new UserPuzzleGateway($db);
$userpuzzle = $gateway->findById($id);

?>

<script src="scripts/userpuzzle.js"></script>

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col">
        <form enctype="multipart/form-data" class="align-items-center" action="userinveditc.php" id="useredit" method="post">
            <input type="hidden" tabindex="-1" name="id" value="<?php echo $userpuzzle->getId() ?>">

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
                    <select class="form-select" name="status" id="status">
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
                <label for="missingPieces" class="form-label"><strong>Missing Pieces</strong></label>
                <div class="input-group">
                    <input type="number" class="form-control rounded-end" name="missingPieces" id="missingPieces" min="0" step="1" value="<?php echo $userpuzzle->getMissingPieces(); ?>">
                    <div id="missingPiecesFeedback"></div>
                </div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="startDate" class="form-label"><strong>Start Date</strong></label>
                <input type="date" class="form-control" name="startDate" id="startDate" value="<?php echo date('Y-m-d', strtotime($userpuzzle->getStart())); ?>">
                <div id="startDateFeedback"></div>
                <a class="btn btn-secondary my-2" onclick="startDate();">Start Puzzle</a>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="endDate" class="form-label"><strong>End Date</strong></label>
                <input type="date" class="form-control" name="endDate" id="endDate" onchange="statusComplete();" value="<?php echo date('Y-m-d', strtotime($userpuzzle->getEnd())); ?>">
                <div id="endDateFeedback"></div>
                <a class="btn btn-secondary mt-2" onclick="endDate();">Complete Puzzle</a>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="difficulty" class="form-label"><strong>Difficulty Rating</strong> - Must be 1 through 5</label>
                <input type="number" class="form-control" name="difficulty" id="difficulty" min="0" max="5" step="1" value="<?php echo $userpuzzle->getDifficulty(); ?>">
                <div id="difficultyFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="quality" class="form-label"><strong>Quality Rating</strong> - Must be 1 through 5</label>
                <input type="number" class="form-control" name="quality" id="quality" min="0" max="5" step="1" value="<?php echo $userpuzzle->getQuality(); ?>">
                <div id="qualityFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="overall" class="form-label"><strong>Overall Rating</strong> - Must be 1 through 5 and can be in .5 increments</label>
                <input type="number" class="form-control" name="overall" id="overall" min="0" max="5" step="0.5" value="<?php echo $userpuzzle->getOverall(); ?>">
                <div id="overallFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="ownership" class="form-label"><strong>Ownership Status</strong></label>
                <div class="">
                    <select class="form-select" name="ownership" id="ownership">
                        <?php
                        foreach ($ownerships as $ownership) {
                            if (!($ownership instanceof Ownership)) continue;
                            echo
                            "<option " . ($ownership->getId() === $userpuzzle->getOwnership()->getId() ? "selected" : "") . " value='" . $ownership->getId() . "'>" . $ownership->getDescription() . "</option>";
                        } ?>
                    </select>
                </div>

            </div>
            <div class="p-2 mb-2 mx-1">
                <label for="loanedoutto" class="form-label"><strong>Loaned To</strong></label>
                <input type="text" class="form-control" name="loanedoutto" id="loanedoutto" value="<?php echo $userpuzzle->getLoaned(); ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <label for="picture" class="form-label"><strong>Completed Picture</strong></label>
                <div class="input-group">
                    <input type="file" accept="image/png, image/jpeg" class="form-control" name="picture" id="picture" max="1">
                    <button type="button" class="btn btn-outline-danger" id="pictureclear"><i class="bi bi-trash"></i> Clear</button>
                </div>
            </div>

            <?php
            if (!empty($userpuzzle->getPicture())) {
                echo
                "<div class='p-2 mb-2 mx-1'>Current picture is " . $userpuzzle->getPicture() . ". If you want to delete it, click the button
                <a class='btn btn-danger p-2 mb-2 mx-1' href='completedelete.php?id=" . $userpuzzle->getId() . "'>Delete Picture</a></div>";
            } ?>

            <div class="p-2 mb-2 mx-1">
                <button type="submit" class="btn btn-primary" name="submit" id="submit">Submit</button>
                <a class="btn btn-danger" name="cancel" href="userinv.php">Cancel</a>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">
    // Set start date input field to today's date and a red color
    function startDate() {
        document.getElementById("startDate").valueAsDate = new Date()
        document.getElementById("startDate").style.backgroundColor = "#58151c";
        document.getElementById("status").value = "<?php echo $inProgress; ?>"
    }

    // Set end date input field to today's date and a red color
    function endDate() {
        document.getElementById("endDate").valueAsDate = new Date();
        document.getElementById("endDate").style.backgroundColor = "#58151c";
        document.getElementById("status").value = "<?php echo $complete; ?>"
    }

    // If end date field is changed set status to completed
    function statusComplete() {
        document.getElementById("status").value = "<?php echo $complete; ?>"
    }
</script>