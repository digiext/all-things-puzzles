<?php

use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\gateway\UserPuzzleGateway as Gateway;

require_once __DIR__ . '/../../api_utils.php';
require_once __DIR__ . '/../../../util/files.php';

require_permissions(PERM_ADD_USER_INVENTORY);
$req = $_SERVER['REQUEST_METHOD'];

if ($req == PUT) {
    global $db;
    $gateway = new Gateway($db);

    global $_PUT;
    parse_put_data();

    $fields = array_filter($_PUT, fn ($k) => in_array($k, array_merge(UINV_FIELDS, [PUZ_ID])), ARRAY_FILTER_USE_KEY);

    must_exist([
        PUZ_ID,
    ], $fields);

    global $auth;
    $puzzleid = $fields[PUZ_ID];
    $userid = $auth->getUser()->getId();
    $sgateway = new StatusGateway($db);
    $status = $fields[UINV_STATUS] ?? $sgateway->findByName('To Do');
    $missingpieces = $fields[UINV_MISSING] ?? 0;
    $start = $fields[UINV_STARTDATE] ?? '';
    $end = $fields[UINV_ENDDATE] ?? '';
    $totaldays = $fields[UINV_TOTALDAYS] ?? 0;
    $difficultyrating = $fields[UINV_DIFFICULTY] ?? 0;
    $qualityrating = $fields[UINV_QUALITY] ?? 0;
    $overallrating = $fields[UINV_OVERALL] ?? 0;
    $ownership = $fields[UINV_OWNERSHIP] ?? 1;
    $loanoutto = $fields[UINV_LOANED] ?? '';

    $uipuz = $gateway->create($userid, $puzzleid, $status, $missingpieces, $start, $end, $totaldays, $difficultyrating, $qualityrating, $overallrating, $ownership, $loanoutto);

    if (!$uipuz) {
        error([
            ERROR_CODE => 'failed_to_add_puzzle',
            MESSAGE => 'Failed to add puzzle to user inventory!'
        ]);
    }

    success($uipuz, 201);
} else wrong_method([PUT]);