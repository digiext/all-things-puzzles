<?php
use puzzlethings\src\gateway\UserGateway as Gateway;

require_once __DIR__ . "/../../api_utils.php";

require_permissions(PERM_PROFILE);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == POST) {
    try {
        global $db;
        $gateway = new Gateway($db);

        if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_USER, 404);
        $user = $gateway->findById($_POST[ID]);
        if ($user == null) error(API_ERROR_INVALID_USER, 404);

        global $auth;
        if ($_POST[ID] != $auth->getUser()->getId() && $auth->getUser()->getGroupId() !== GROUP_ID_ADMIN) error([
                ERROR_CODE => "cant_edit_other_user",
                MESSAGE => "You are not able to edit other users!"
        ]);

        $failed = false;
        if (array_key_exists('username', $_POST)) {
            $failed |= !$gateway->updateUsername($user, $_POST['username']);
        }

        if (array_key_exists('display_name', $_POST)) {
            $failed |= !$gateway->updateFullname($user, $_POST['display_name']);
        }

        if (array_key_exists('email', $_POST)) {
            $failed |= !$gateway->updateEmail($user, $_POST['email']);
        }

        if (!$failed) {
            success($gateway->findById($_POST[ID]));
        } else {
            error([
                ERROR_CODE => "user_update_failed",
                MESSAGE => "One or more fields failed to update"
            ]);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else wrong_method([POST]);
