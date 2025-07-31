<?php
use puzzlethings\src\gateway\UserGateway as Gateway;

require_once __DIR__ . "/../api_utils.php";

$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $gateway = new Gateway($db);

        $searchOptions = search_options(USER_ID, USER_FILTERS);

        $count = $gateway->count($searchOptions);
        $res = $gateway->findAll($searchOptions, true);

        if ($res instanceof PDOException) database_error();
        else if ($res == null) success([]);
        else {
            if (is_authed()) $res = array_map(fn($itm) => array_merge($itm->jsonSerializeMin(), [LINK => api_link('/api/user/' . $itm->getId() . '/')]), $res);
            else $res = array_map(fn ($itm) => $itm->jsonSerializeMin(), $res);
            success_with_pagination($res, $count);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else wrong_method([GET]);