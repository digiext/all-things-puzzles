<?php
use puzzlethings\src\gateway\UserGateway as Gateway;

require_once __DIR__ . "/../api_utils.php";

require_permissions(PERM_READ_PROFILE);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $gateway = new Gateway($db);

        global $auth;
        if (($_GET[ID] ?? $auth->getUser()->getId()) == null) error(API_ERROR_INVALID_USER, 404);
        $user = $gateway->findById($_GET[ID] ?? $auth->getUser()->getId());
        if ($user == null) error(API_ERROR_INVALID_USER, 404);

        global $auth;

        if ($user == null) {
            error(API_ERROR_INVALID_USER, 404);
        } else {
            if (($_GET[ID] ?? $auth->getUser()->getId()) != $auth->getUser()->getId() && $auth->getUser()->getGroupId() !== GROUP_ID_ADMIN) success($user->jsonSerializeMin());
            success($user->jsonSerialize());
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else {
    wrong_method([GET]);
}