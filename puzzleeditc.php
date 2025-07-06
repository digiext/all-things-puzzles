<?php

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\gateway\SourceGateway;
use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\gateway\LocationGateway;
use puzzlethings\src\object\Brand;
use puzzlethings\src\object\Disposition;
use puzzlethings\src\object\Location;
use puzzlethings\src\object\Source;

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
$hasfile = isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE;

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $oldpicture = $_POST['oldpicture'];
    $deleteoldpic = $_POST['deleteoldpic'];

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
    $puzzle = $gateway->findById($id);
    $picture = null;

    if ($hasfile) {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir('images');
            mkdir('images/uploads');
            mkdir('images/uploads/thumbnails');
        }

        $status = $_FILES['picture']['error'];
        $tmp = $_FILES['picture']['tmp_name'];

        if ($status !== UPLOAD_ERR_OK) {
            warningAlert(FILE_MESSAGES[$status], "puzzleedit.php?id=" . $id);
        }

        $filesize = filesize($tmp);
        if ($filesize > MAX_FILE_SIZE) {
            warningAlert("File too large! Must be under 5MB!", "puzzleedit.php?id=" . $id);
        }

        $mimetype = getMimeType($tmp);
        if (!in_array($mimetype, array_keys(ALLOWED_IMAGE_TYPES))) {
            warningAlert("Invalid file type! Must be a PNG or JPEG", "puzzleedit.php?id=" . $id);
        }

        $uploadedFile = str_replace(" ", "_", htmlspecialchars($puzname)) . "_" . $puzzle->getId() . '.' . ALLOWED_IMAGE_TYPES[$mimetype];
        $filepath = UPLOAD_DIR_ABSOLUTE . '/' . $uploadedFile;

        $success = move_uploaded_file($tmp, $filepath);
        if ($success) {
            $picture = $uploadedFile;
        }
    }

    if ($deleteoldpic === true || $deleteoldpic === 'true') {
        $picurl = $puzzle->getPicture();

        if (($picurl ?? '') != '') {
            $success = unlink(UPLOAD_DIR_ABSOLUTE . '/'.  $picurl);
            if (!$success) {
                error_log("Failed deleting file $picture");
                warningAlertNoRedir("Failed removing picture from server");
            }
        }
    }

    $values = [
        PUZ_NAME => $puzname,
        PUZ_PIECES => $pieces,
        PUZ_BRAND_ID => $brand instanceof Brand ? $brand->getId() : $brand,
        PUZ_COST => $cost,
        PUZ_DATE_ACQUIRED => $acquired,
        PUZ_SOURCE_ID => $source instanceof Source ? $source->getId() : $source,
        PUZ_LOCATION_ID => $location instanceof Location ? $location->getId() : $location,
        PUZ_DISPOSITION_ID => $disposition instanceof Disposition ? $disposition->getId() : $disposition,
        PUZ_PICTURE_URL => $hasfile ? $picture : ($deleteoldpic ? null : $oldpicture),
        PUZ_UPC => $upc,
    ];

    $code = $gateway->update($id, $values);

    session_start();
    if ($code === false) {
        failAlert("Puzzle Not Updated!");

        if ($hasfile && ($picurl ?? '') != '') {
            $success = unlink(UPLOAD_DIR . '/' . $picture);
            if (!$success) {
                error_log("Failed deleting file $picture");
                warningAlertNoRedir("Failed removing picture from server");
            }
        }
    } else {
        successAlert("Puzzle Updated!");
    }

    header("Location: puzzleinv.php");
}
