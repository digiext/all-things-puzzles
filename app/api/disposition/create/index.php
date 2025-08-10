<?php

use puzzlethings\src\gateway\DispositionGateway as Gateway;

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

    $disposition = $gateway->create($_PUT['description']);

    if ($disposition) {
        success($disposition);
    } else {
        error([
            ERROR_CODE => 'failed_to_create_disposition',
            MESSAGE => 'Failed to create disposition!'
        ]);
    }
} else wrong_method([PUT]);