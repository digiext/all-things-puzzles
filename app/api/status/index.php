<?php
use puzzlethings\src\gateway\StatusGateway as Gateway;

require_once __DIR__ . "/../api_utils.php";

$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $gateway = new Gateway($db);

        $id = $_GET['id'] ?? null;
        $data = $gateway->findById($id);

        if ($data == null) {
            error(API_ERROR_INVALID_STATUS);
        } else {
            success($data);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else {
    wrong_method([GET]);
}