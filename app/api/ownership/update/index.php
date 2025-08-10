<?php

use puzzlethings\src\gateway\OwnershipGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_READ_MISC | PERM_EDIT_MISC);
require_admin();
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_OWNERSHIP, 404);
    $ownership = $gateway->findById($_POST[ID]);
    if ($ownership == null) error(API_ERROR_INVALID_OWNERSHIP, 404);

    if (array_key_exists('description', $_POST)) {
        $ownership = $gateway->updateDesc($ownership, $_POST['description']);

        if ($ownership) {
            success($ownership);
        } else {
            error([
                ERROR_CODE => 'failed_to_update_ownership',
                MESSAGE => 'Failed to update ownership!'
            ]);
        }
    } else success($ownership);
} else wrong_method([POST]);