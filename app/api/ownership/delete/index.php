<?php
use puzzlethings\src\gateway\OwnershipGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_DELETE_MISC);
require_admin();
$req = $_SERVER['REQUEST_METHOD'];
if ($req == DELETE) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_OWNERSHIP, 404);
    $success = $gateway->delete($_POST[ID]);

    if ($success) {
        deleted();
    } else {
        error([
            ERROR_CODE => "failed_to_delete_ownership",
            MESSAGE => "Failed to delete ownership"
        ]);
    }
} else wrong_method([DELETE]);