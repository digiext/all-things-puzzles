<?php

use puzzlethings\src\gateway\CategoryGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_CREATE_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == PUT) {
    global $db;
    $gateway = new Gateway($db);

    global $_PUT;
    parse_put_data();

    must_exist([
        'description'
    ], $_PUT);

    $category = $gateway->create($_PUT['description']);

    if ($category) {
        success($category);
    } else {
        error([
            ERROR_CODE => 'failed_to_create_category',
            MESSAGE => 'Failed to create category!'
        ]);
    }
} else wrong_method([PUT]);