<?php
use puzzlethings\src\gateway\PuzzleWishGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_DELETE_WISHLIST);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == DELETE) {
    global $db;
    $gateway = new Gateway($db);

    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_WISHLIST_PUZZLE);
    $pwish = $gateway->findById($_POST[ID]);

    if ($pwish == null) error(API_ERROR_INVALID_WISHLIST_PUZZLE);

    global $auth;
    if ($pwish->getUserId() != $auth->getUser()->getId() && !is_admin()) {
        error([
            ERROR_CODE => 'cant_delete_other_user_wishlist',
            MESSAGE => 'You do not have permission to delete this wishlist puzzle!'
        ]);
    }

    $success = $gateway->delete($pwish);

    if ($success) {
        deleted();
    } else {
        error([
            ERROR_CODE => "failed_to_delete_wishlist_puzzle",
            MESSAGE => "Failed to delete wishlist puzzle"
        ]);
    }
} else wrong_method([DELETE]);