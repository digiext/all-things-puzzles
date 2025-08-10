<?php

use puzzlethings\src\gateway\CategoryGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_READ_MISC | PERM_EDIT_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_CATEGORY, 404);
    $category = $gateway->findById($_POST[ID]);
    if ($category == null) error(API_ERROR_INVALID_CATEGORY, 404);

    if (array_key_exists('description', $_POST)) {
        $category = $gateway->updateDesc($category, $_POST['description']);

        if ($category) {
            success($category);
        } else {
            error([
                ERROR_CODE => 'failed_to_update_category',
                MESSAGE => 'Failed to update category!'
            ]);
        }
    } else success($category);
} else wrong_method([POST]);