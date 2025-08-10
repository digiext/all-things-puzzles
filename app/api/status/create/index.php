<?php

use puzzlethings\src\gateway\StatusGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_CREATE_MISC);
require_admin();
$req = $_SERVER['REQUEST_METHOD'];
if ($req == PUT) {
    global $db;
    $gateway = new Gateway($db);

    global $_PUT;
    parse_put_data();

    must_exist([
        'description'
    ], $_PUT);

    $status = $gateway->create($_PUT['description']);

    if ($status) {
        success($status);
    } else {
        error([
            ERROR_CODE => 'failed_to_create_status',
            MESSAGE => 'Failed to create status!'
        ]);
    }
} else wrong_method([PUT]);