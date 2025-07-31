<?php

use puzzlethings\src\gateway\DispositionGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_READ_MISC | PERM_EDIT_MISC);
require_admin();
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_DISPOSITION);
    $disposition = $gateway->findById($_POST[ID]);
    if ($disposition == null) error(API_ERROR_INVALID_DISPOSITION);

    if (array_key_exists('description', $_POST)) {
        $disposition = $gateway->updateDesc($disposition, $_POST['description']);

        if ($disposition) {
            success($disposition);
        } else {
            error([
                ERROR_CODE => 'failed_to_update_disposition',
                MESSAGE => 'Failed to update disposition!'
            ]);
        }
    } else success($disposition);
} else wrong_method([POST]);