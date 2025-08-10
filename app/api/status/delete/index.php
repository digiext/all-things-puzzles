<?php
use puzzlethings\src\gateway\StatusGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_DELETE_MISC);
require_admin();
$req = $_SERVER['REQUEST_METHOD'];
if ($req == DELETE) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_STATUS, 404);
    $success = $gateway->delete($_POST[ID]);

    if ($success) {
        deleted();
    } else {
        error([
            ERROR_CODE => "failed_to_delete_status",
            MESSAGE => "Failed to delete status"
        ]);
    }
} else wrong_method([DELETE]);