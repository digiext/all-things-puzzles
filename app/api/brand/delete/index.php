<?php
use puzzlethings\src\gateway\BrandGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_DELETE_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == DELETE) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_BRAND, 404);
    $success = $gateway->delete($_POST[ID]);

    if ($success) {
        deleted();
    } else {
        error([
            ERROR_CODE => "failed_to_delete_brand",
            MESSAGE => "Failed to delete brand"
        ]);
    }
} else wrong_method([DELETE]);