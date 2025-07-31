<?php

use puzzlethings\src\gateway\CategoryGateway;
use puzzlethings\src\gateway\PuzzleGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';
require_once __DIR__ . '/../../../util/files.php';

require_permissions(PERM_CREATE_PUZZLE);
$req = $_SERVER['REQUEST_METHOD'];

const UPLOAD_DIR = 'images/uploads/thumbnails';
const UPLOAD_DIR_ABSOLUTE = __DIR__ . '/../../../' . UPLOAD_DIR;

if ($req == PUT) {
    global $db;
    $gateway = new Gateway($db);

    global $_PUT;
    parse_multipart_form_data();

    $fields = array_filter($_PUT, fn ($k) => in_array($k, array_merge(PUZ_FIELDS, ['category'])), ARRAY_FILTER_USE_KEY);

    must_exist([
        PUZ_NAME,
        PUZ_PIECES,
        PUZ_BRAND_ID,
        PUZ_COST,
        PUZ_DATE_ACQUIRED,
        PUZ_SOURCE_ID,
        PUZ_LOCATION_ID,
        PUZ_DISPOSITION_ID,
    ], $fields);

    $puzname = $fields[PUZ_NAME];
    $pieces = $fields[PUZ_PIECES];
    $brand = $fields[PUZ_BRAND_ID];
    $cost = $fields[PUZ_COST];
    $acquired = $fields[PUZ_DATE_ACQUIRED];
    $source = $fields[PUZ_SOURCE_ID];
    $location = $fields[PUZ_LOCATION_ID];
    $disposition = $fields[PUZ_DISPOSITION_ID];
    $upc = $fields[PUZ_UPC] ?? "";
    $categories = $fields['category'] ?? [];
    if (!is_array($categories)) $categories = [$categories];

    $puzzle = $gateway->create($puzname, $pieces, $brand, $cost, $acquired, $source, $location, $disposition, $upc);

    $addfailed = false;
    if (!empty($categories)) {
        $cgateway = new CategoryGateway($db);
        foreach ($categories as $category) {
            $addfailed |= !$cgateway->createPuzzle($puzzle->getId(), $category);
        }
    }

    if ($addfailed) {
        error([
            ERROR_CODE => "add_category_failed",
            MESSAGE => "Puzzle created, however, one or more categories failed to add!"
        ], 500);
    }

    $hasfile = isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK;

    if ($hasfile) {
        if (!file_exists(UPLOAD_DIR_ABSOLUTE)) {
            mkdir('app/images');
            mkdir('app/images/uploads');
            mkdir('app/images/uploads/thumbnails');
        }

        $status = $_FILES['picture']['error'];
        $tmp = $_FILES['picture']['tmp_name'];

        if ($status !== UPLOAD_ERR_OK && $status !== UPLOAD_ERR_NO_FILE) {
            error([
                ERROR_CODE => "file_upload_error",
                MESSAGE => "Puzzle created, however, " . FILE_MESSAGES[$status]
            ], 500);
        }

        if ($status === UPLOAD_ERR_NO_FILE) {
            success($puzzle);
        }

        $filesize = filesize($tmp);
        if ($filesize > MAX_FILE_SIZE) {
            error([
                ERROR_CODE => "file_size_error",
                MESSAGE => "Puzzle created, however, file too large! Must be under 5MB!"
            ], 400);
        }

        $mimetype = getMimeType($tmp);
        if (!in_array($mimetype, array_keys(ALLOWED_IMAGE_TYPES))) {
            error([
                ERROR_CODE => "file_type_error",
                MESSAGE => "Puzzle created, however, invalid file type! Must be a PNG or JPEG!"
            ], 400);
        }

        $uploadedFile = str_replace([" ", "%"], "_", urlencode($puzzle->getName())) . "_" . $puzzle->getId() . '.' . ALLOWED_IMAGE_TYPES[$mimetype];
        $filepath = UPLOAD_DIR_ABSOLUTE . '/' . $uploadedFile;

        echo json_encode($_FILES['picture']);

        $success = move_uploaded_file($tmp, $filepath);
        if ($success) {
//            $code = $gateway->update($puzzle, [
//                PUZ_PICTURE_URL => $uploadedFile,
//            ]);

            $data = $puzzle->jsonSerialize();
            $data[PUZ_PICTURE_URL] = $uploadedFile;
            success($data);
        }

        // warningAlert("Puzzle successfully created, however, thumbnail failed to save!");
        error([
            ERROR_CODE => "file_upload_error",
            MESSAGE => "Puzzle created, however, the file failed to transfer!"
        ], 500);
    } else success($puzzle);
} else wrong_method([PUT]);