<?php

use puzzlethings\src\gateway\PuzzleWishGateway;
use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

const UPLOAD_DIR = '/images/uploads/thumbnails';
const UPLOAD_DIR_ABSOLUTE = __DIR__ . UPLOAD_DIR;

$brandname = $_POST['brandName'];

if (isset($_POST['submit'])) {
    $id = $_POST['id'];

    $puzname = $_POST['puzname'];
    $pieces = $_POST['pieces'];
    $brand = $_POST['brand'];
    $upc = $_POST['upc'];

    if (!empty($brandname)) {
        $gateway = new BrandGateway($db);
        $code = $gateway->create($brandname);
        $brand = $code;
    }
    $gateway = new PuzzleWishGateway($db);


    $values = [
        PUZ_NAME => $puzname,
        PUZ_PIECES => $pieces,
        PUZ_BRAND_ID => $brand instanceof Brand ? $brand->getId() : $brand,
        PUZ_UPC => $upc,
    ];

    $code = $gateway->update($id, $values);

    //session_start();
    if ($code === false) {
        failAlert("Puzzle Wishlist Not Updated!");
    } else {
        successAlert("Puzzle Wishlist Updated!");
    }

    header("Location: puzzlewish.php");
}
