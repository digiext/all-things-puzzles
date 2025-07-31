<?php

use puzzlethings\src\gateway\StatusGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_READ_MISC | PERM_EDIT_MISC);
require_admin();
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_STATUS);
    $status = $gateway->findById($_POST[ID]);
    if ($status == null) error(API_ERROR_INVALID_STATUS);

    if (array_key_exists('description', $_POST)) {
        $status = $gateway->updateDesc($status, $_POST['description']);

        if ($status) {
            success($status);
        } else {
            error([
                ERROR_CODE => 'failed_to_update_status',
                MESSAGE => 'Failed to update status!'
            ]);
        }
    } else success($status);
} else wrong_method([POST]);