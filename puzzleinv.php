<?php
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\PuzzleGateway;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Puzzle Inventory';
include 'header.php';
include 'nav.php';

?>


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