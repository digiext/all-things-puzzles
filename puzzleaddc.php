<?php

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\gateway\SourceGateway;
use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\gateway\LocationGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/files.php';

const UPLOAD_DIR = '/images/uploads/thumbnails';
const UPLOAD_DIR_ABSOLUTE = __DIR__ . UPLOAD_DIR;


$brandname = $_POST['brandName'];
$sourcedesc = $_POST['sourceDesc'];
$dispositiondesc = $_POST['dispositionDesc'];
$locationdesc = $_POST['locationDesc'];
$hasfile = isset($_FILES['picture']);

if (isset($_POST['submit'])) {
    $puzname = $_POST['puzname'];
    $pieces = $_POST['pieces'];
    $brand = $_POST['brand'];
    $cost = $_POST['cost'];
    $acquired = $_POST['acquired'];
    $source = $_POST['source'];
    $upc = $_POST['upc'];
    $disposition = $_POST['disposition'];
    $location = $_POST['location'];

    if (!empty($brandname)) {
        $gateway = new BrandGateway($db);
        $code = $gateway->create($brandname);
        $brand = $code;
    }
    if (!empty($sourcedesc)) {
        $gateway = new SourceGateway($db);
        $code = $gateway->create($sourcedesc);
        $source = $code;
    }
    if (!empty($dispositiondesc)) {
        $gateway = new DispositionGateway($db);
        $code = $gateway->create($dispositiondesc);
        $disposition = $code;
    }
    if (!empty($locationdesc)) {
        $gateway = new LocationGateway($db);
        $code = $gateway->create($locationdesc);
        $location = $code;
    }

    $gateway = new PuzzleGateway($db);
    $puzzle = $gateway->create($puzname, $pieces, $brand, $cost, $acquired, $source, $location, $disposition, $upc);

    session_start();
    if ($puzzle === false) {
        failAlert("Puzzle Not Created!");
    } else {
        if ($hasfile) {
            if (!is_dir(UPLOAD_DIR_ABSOLUTE)) {
                mkdir('images');
                mkdir('images/uploads');
                mkdir('images/uploads/thumbnails');
            }

            $status = $_FILES['picture']['error'];
            $tmp = $_FILES['picture']['tmp_name'];

            if ($status !== UPLOAD_ERR_OK && $status !== UPLOAD_ERR_NO_FILE) {
                warningAlert(FILE_MESSAGES[$status], "puzzleadd.php");
            }

            $filesize = filesize($tmp);
            if ($filesize > MAX_FILE_SIZE) {
                warningAlert("File too large! Must be under 5MB!", "puzzleadd.php");
            }

            $mimetype = getMimeType($tmp);
            if (!in_array($mimetype, array_keys(ALLOWED_IMAGE_TYPES))) {
                warningAlert("Invalid file type! Must be a PNG or JPEG", "puzzleadd.php");
            }

            $uploadedFile = str_replace([" ", "%"], "_", urlencode($puzzle->getName())) . "_" . $puzzle->getId() . '.' . ALLOWED_IMAGE_TYPES[$mimetype];
            $filepath = UPLOAD_DIR_ABSOLUTE . '/' . $uploadedFile;

            $success = move_uploaded_file($tmp, $filepath);
            if ($success) {
                $code = $gateway->update($puzzle, [
                    PUZ_PICTURE_URL => $uploadedFile,
                ]);

                successAlert("Puzzle has been created!");
            }

            warningAlert("Puzzle successfully created, however, thumbnail failed to save!");
        } else successAlert("Puzzle has been created");
    }

    header("Location: home.php");
}
