<?php
use puzzlethings\src\gateway\PuzzleWishGateway as Gateway;

require_once __DIR__ . "/../../api_utils.php";

require_permissions(PERM_EDIT_WISHLIST);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    try {
        global $db;
        $gateway = new Gateway($db);

        if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_WISHLIST_PUZZLE);
        $wish = $gateway->findById($_POST[ID]);
        if ($wish == null) error(API_ERROR_INVALID_WISHLIST_PUZZLE);

        global $auth;
        if ($_POST[ID] != $auth->getUser()->getId() && !is_admin()) error([
                ERROR_CODE => "cant_edit_other_user_wishlist",
                MESSAGE => "You do not have permission to edit other user's wishlists!"
        ]);

        $update = array_filter($_POST, fn ($k) => in_array($k, [PUZ_NAME, PUZ_PIECES, PUZ_BRAND_ID, PUZ_UPC]), ARRAY_FILTER_USE_KEY);
        constrain(PUZ_PIECES, $update, fn ($pieces) => max(0, $pieces));
        $success = $gateway->update($wish, $update);

        if ($success) {
            success($gateway->findById($_POST[ID]));
        } else {
            error([
                ERROR_CODE => "wishlist_puzzle_update_failed",
                MESSAGE => "One or more fields failed to update"
            ]);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else wrong_method([POST]);
