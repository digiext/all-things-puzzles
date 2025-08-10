<?php
use puzzlethings\src\gateway\PuzzleWishGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';
require_once __DIR__ . '/../../../util/files.php';

require_permissions(PERM_CREATE_WISHLIST);
$req = $_SERVER['REQUEST_METHOD'];

if ($req == PUT) {
    global $db;
    $gateway = new Gateway($db);

    global $_PUT;
    parse_put_data();

    $fields = array_filter($_PUT, fn ($k) => in_array($k, PUZ_FIELDS), ARRAY_FILTER_USE_KEY);

    must_exist([
        PUZ_NAME,
        PUZ_PIECES,
        PUZ_BRAND_ID,
    ], $fields);

    $puzname = $fields[PUZ_NAME];
    $pieces = $fields[PUZ_PIECES];
    $brand = $fields[PUZ_BRAND_ID];
    $upc = $fields[PUZ_UPC] ?? "";

    global $auth;
    $puzzle = $gateway->create($auth->getUser()->getId(), $puzname, $pieces, $brand, $upc);

    if (!$puzzle) {
        error([
            ERROR_CODE => 'failed_to_create_wishlist_puzzle',
            MESSAGE => 'Failed to create wishlist puzzle!'
        ]);
    }

    success($puzzle, 201);
} else wrong_method([PUT]);