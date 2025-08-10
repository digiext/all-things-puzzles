<?php
use puzzlethings\src\gateway\UserGateway as Gateway;
use puzzlethings\src\gateway\UserPuzzleGateway;

require_once __DIR__ . "/../api_utils.php";

require_permissions(PERM_READ_WISHLIST);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $gateway = new Gateway($db);
        $upuzGateway = new UserPuzzleGateway($db);

        global $auth;
        $id = $_GET[ID] ?? $auth->getUser()->getId();
        if (!is_admin()) $id = $auth->getUser()->getId();
        if ($id == null) error(API_ERROR_INVALID_USER, 404);

        $user = $gateway->findById($id);
        if ($user == null) error(API_ERROR_INVALID_USER, 404);

        $uinv = $upuzGateway->findByUserId($user->getId());
        if ($uinv == null && $uinv != array()) error([
            ERROR_CODE => 'invalid_wishlist',
            MESSAGE => 'Wishlist not found!'
        ]);
        else success($uinv);
    } catch (Error $e) {
        bad_request($e);
    }
} else {
    wrong_method([GET]);
}