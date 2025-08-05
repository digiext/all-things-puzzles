<?php
use puzzlethings\src\gateway\PuzzleGateway as Gateway;

require_once __DIR__ . "/../api_utils.php";

require_permissions(PERM_READ_PUZZLE);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $gateway = new Gateway($db);

        $id = $_GET[ID] ?? null;
        $data = $gateway->findById($id);

        if ($data == null) {
            error(API_ERROR_INVALID_PUZZLE);
        } else {
            success($data);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else {
    wrong_method([GET]);
}