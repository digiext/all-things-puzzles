<?php
use puzzlethings\src\gateway\UserPuzzleGateway as Gateway;

require_once __DIR__ . "/../../api_utils.php";

require_permissions(PERM_EDIT_USER_INVENTORY);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    try {
        global $db;
        $gateway = new Gateway($db);

        if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_USER_INVENTORY_PUZZLE, 404);
        $uipuz = $gateway->findById($_POST[ID]);
        if ($uipuz == null) error(API_ERROR_INVALID_USER_INVENTORY_PUZZLE, 404);

        global $auth;
        if ($_POST[ID] != $auth->getUser()->getId() && !is_admin()) error([
                ERROR_CODE => "cant_edit_other_user_inventory",
                MESSAGE => "You do not have permission to edit other user's inventories!"
        ]);

        $update = array_filter($_POST, fn ($k) => in_array($k, UINV_FIELDS), ARRAY_FILTER_USE_KEY);
        constrain(UINV_MISSING, $update, fn ($pieces) => max(0, $pieces));
        constrain(UINV_DIFFICULTY, $update, fn ($diff) => max(0, round($diff)));
        constrain(UINV_QUALITY, $update, fn ($qual) => max(0, round($qual)));
        constrain(UINV_OVERALL, $update, fn ($overall) => max(0, round($overall * 2) / 2));
        $success = $gateway->update($uipuz, $update);

        if ($success) {
            success($gateway->findById($_POST[ID]));
        } else {
            error([
                ERROR_CODE => "user_inventory_puzzle_update_failed",
                MESSAGE => "One or more fields failed to update"
            ]);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else wrong_method([POST]);
