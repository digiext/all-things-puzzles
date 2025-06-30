<?php
global $db;
include 'util/function.php';
require 'util/db.php';

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\object\Puzzle;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Puzzle Inventory';
include 'header.php';
include 'nav.php';

$options = ["page" => 0, "maxperpage" => 8];

$gateway = new PuzzleGateway($db);
$puzzles = $gateway->findAll($options);

?>


<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Puzzle Inventory</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="home.php">Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>

<div class="container my-2 table-responsive">
    <table
        id="table"
        data-classes="table table-dark table-striped table-hover"
        data-toggle="table"
        data-pagination="true"
        data-search="true"
        data-buttons-toolbar=".buttons-toolbar"
        data-page-list="10,25,50,100,all"
        data-search-on-enter-key="false"
        data-id-field="">

        <!-- <thead>
            <tr>
                <th scope="col" class="col-6"></th>
                <th scope="col" class="col-6"></th>
            </tr>
        </thead> -->

        <tbody class="table-group-divider">
            <tr class="m-2">
                <?php
                foreach ($puzzles as $puzzle) {
                    if (!($puzzle instanceof Puzzle)) continue;
                    echo
                    "<td class='m-2'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title' id='cardname-" . $puzzle->getId() . "'>" . $puzzle->getName() . "</h5>
                            <p class='card-subtitle text-body-secondary' id='cardbrand-" . $puzzle->getId() . "'>" . $puzzle->getBrand()->getName() . "</p>
                        </div>
                        <ul class='list-group list-group-flush'>
                            <li class='list-group-item hstack gap-2'><i class='input-group-text p-2 bi bi-puzzle'></i><span id='cardpieces-" . $puzzle->getId() . "'>" . $puzzle->getPieces() . "</span></li>
                            <li class='list-group-item hstack gap-2'><span class='input-group-text py-1'>$</span><span id='cardcost-" . $puzzle->getId() . "'>" . $puzzle->getCost() . "</span><span id='cardcurrency-" . $puzzle->getId() . "'>USD</span></li>
                            <li class='list-group-item hstack gap-2'><i class='input-group-text p-2 bi bi-stars'></i><span id='cardsource-" . $puzzle->getId() . "'>" . $puzzle->getSource()->getDescription() . "</span></li>
                            <li class='list-group-item hstack gap-2'><i class='input-group-text p-2 bi bi-qr-code'></i><span id='cardupc-" . $puzzle->getId() . "'>" . $puzzle->getUpc() . "</span></li>
                        </ul>
                    </div>
                </td>";
                }
                ?>
            </tr>

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
        </tbody>
    </table>