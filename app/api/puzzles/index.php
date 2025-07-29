<?php
use puzzlethings\src\gateway\PuzzleGateway as Gateway;

require_once __DIR__ . "/../api_utils.php";

$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $gateway = new Gateway($db);

        $searchOptions = search_options(PUZ_ID, PUZ_FILTERS);

        $count = $gateway->count($searchOptions);
        $res = $gateway->findAll($searchOptions);

        if ($res instanceof PDOException) database_error();
        else if ($res == null) success([]);
        else {
            $res = array_map(fn($itm) => array_merge($itm->jsonSerialize(), [LINK => api_link('/api/puzzle/' . $itm->getId() . '/')]), $res);
            success_with_pagination($res, $count);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else wrong_method([GET]);