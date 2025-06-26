<?php
global $db;
include '../util/db.php';
include '../util/function.php';

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

<?php foreach ($gateway as $brand) {
    if (!($brand instanceof Brand)) continue;

    echo "Brand ID: " . $brand->getId() . " | Brand Name: " . $brand->getName();
} ?>