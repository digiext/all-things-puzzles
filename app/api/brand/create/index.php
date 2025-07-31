<?php

use puzzlethings\src\gateway\BrandGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_CREATE_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == PUT) {
    global $db;
    $gateway = new Gateway($db);

    global $_PUT;
    parse_put_data();

    must_exist([
        'name'
    ], $_PUT);

    $brand = $gateway->create($_PUT['name']);

    if ($brand) {
        success($brand);
    } else {
        error([
            ERROR_CODE => 'failed_to_create_brand',
            MESSAGE => 'Failed to create brand!'
        ]);
    }
} else wrong_method([PUT]);