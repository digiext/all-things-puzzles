<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;

//If Not Logged In Reroute to index.php
if (!isAdmin()) {
    header("Location: ../home.php");
}

$title = 'Brands';
include '../header.php';
include '../nav.php';

$gateway = new BrandGateway($db);
$gateway->findAll();
?>

<div class="container my-2 text-center">
    <h3 class="text-center">Brand Table</h3>
</div>

<div class="container my-2">
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Brand</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($gateway as $brand) {
                if (!($brand instanceof Brand)) continue;
                echo "<tr><th scope='row'>" . $brand->getId() . "</th> <td> " . $brand->getName() . "</td></tr>";
            } ?>
        </tbody>
    </table>
</div>