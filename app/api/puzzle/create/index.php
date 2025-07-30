<?php
use puzzlethings\src\gateway\PuzzleGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';

require_permissions(PERM_CREATE_PUZZLE);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == PUT) {
    global $db;
    $gateway = new Gateway($db);
    $update = array_filter($_POST, fn ($k) => in_array($k, array_merge(PUZ_FIELDS, ["picture"])), ARRAY_FILTER_USE_KEY);

    echo json_encode($update);

//    if (($_POST[ID] ?? null) == null) error(API_ERROR_INVALID_PUZZLE);
//    $puzzle = $gateway->findById($_POST[ID]);
//    if ($puzzle == null) error(API_ERROR_INVALID_PUZZLE);
//
//
//    constrain(PUZ_COST, $update, fn ($cost) => max(0, $cost));
//    constrain(PUZ_PIECES, $update, fn ($pieces) => max(0, $pieces));
//    remove_id(PUZ_ID, $update);
//
//    $success = $gateway->update($puzzle, $update);
//    if ($success) {
//        success($gateway->findById(intval($_POST[ID])));
//    } else {
//        error([
//            ERROR_CODE => "update_failed",
//            MESSAGE => "Failed to update puzzle",
//        ], 500);
//    }
} else wrong_method([PUT]);