<?php

use puzzlethings\src\gateway\PuzzleWishGateway;
use puzzlethings\src\gateway\BrandGateway;


global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

const UPLOAD_DIR = '/images/uploads/thumbnails';
const UPLOAD_DIR_ABSOLUTE = __DIR__ . UPLOAD_DIR;


$brandname = $_POST['brandName'];

if (isset($_POST['submit'])) {
    $userid = $_POST['userid'];
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
    $puzzle = $gateway->create($userid, $puzname, $pieces, $brand, $upc);

    // session_start();
    if ($puzzle === false) {
        failAlert("Puzzle Not Created!");
    } else {
        successAlert("Puzzle has been created!");
    }

    header("Location: puzzlewish.php");
}
