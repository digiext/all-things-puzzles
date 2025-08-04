<?php
use puzzlethings\src\gateway\UserPuzzleGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_REMOVE_USER_INVENTORY);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == DELETE) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_USER_INVENTORY_PUZZLE);
    $uipuzid = $gateway->findById($_POST[ID]);

    if ($uipuzid == null) error(API_ERROR_INVALID_USER_INVENTORY_PUZZLE);

    global $auth;
    if ($uipuzid->getUser()->getId() != $auth->getUser()->getId() && !is_admin()) {
        error([
            ERROR_CODE => 'cant_delete_other_user_inventory_puzzle',
            MESSAGE => 'You do not have permission to delete this user inventory puzzle!'
        ]);
    }

    $success = $gateway->delete($uipuzid);

    if ($success) {
        deleted();
    } else {
        error([
            ERROR_CODE => "failed_to_delete_user_inventory_puzzle",
            MESSAGE => "Failed to delete user inventory puzzle"
        ]);
    }
} else wrong_method([DELETE]);