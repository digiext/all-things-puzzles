<?php
use puzzlethings\src\gateway\PuzzleGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_DELETE_PUZZLE);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == DELETE) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_PUZZLE);
    $success = $gateway->delete($_POST[ID]);

    if ($success) {
        deleted();
    } else {
        error([
            ERROR_CODE => "delete_failed",
            MESSAGE => "Failed to delete puzzle"
        ]);
    }
} else wrong_method([DELETE]);