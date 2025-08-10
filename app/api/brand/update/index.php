<?php

use puzzlethings\src\gateway\BrandGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_READ_MISC | PERM_EDIT_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_BRAND, 404);
    $brand = $gateway->findById($_POST[ID]);
    if ($brand == null) error(API_ERROR_INVALID_BRAND, 404);

    if (array_key_exists('name', $_POST)) {
        $brand = $gateway->updateName($brand, $_POST['name']);

        if ($brand) {
            success($brand);
        } else {
            error([
                ERROR_CODE => 'failed_to_update_brand',
                MESSAGE => 'Failed to update brand!'
            ]);
        }
    } else success($brand);
} else wrong_method([POST]);