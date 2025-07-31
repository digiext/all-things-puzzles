<?php

use puzzlethings\src\gateway\LocationGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_READ_MISC | PERM_EDIT_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_LOCATION);
    $location = $gateway->findById($_POST[ID]);
    if ($location == null) error(API_ERROR_INVALID_LOCATION);

    if (array_key_exists('description', $_POST)) {
        $location = $gateway->updateDesc($location, $_POST['description']);

        if ($location) {
            success($location);
        } else {
            error([
                ERROR_CODE => 'failed_to_update_location',
                MESSAGE => 'Failed to update location!'
            ]);
        }
    } else success($location);
} else wrong_method([POST]);