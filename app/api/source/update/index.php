<?php

use puzzlethings\src\gateway\SourceGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_READ_MISC | PERM_EDIT_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_SOURCE);
    $source = $gateway->findById($_POST[ID]);
    if ($source == null) error(API_ERROR_INVALID_SOURCE);

    if (array_key_exists('description', $_POST)) {
        $source = $gateway->updateDesc($source, $_POST['description']);

        if ($source) {
            success($source);
        } else {
            error([
                ERROR_CODE => 'failed_to_update_source',
                MESSAGE => 'Failed to update source!'
            ]);
        }
    } else success($source);
} else wrong_method([POST]);