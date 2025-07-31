<?php
use puzzlethings\src\gateway\OwnershipGateway as Gateway;

require_once __DIR__ . "/../api_utils.php";

require_permissions(PERM_READ_MISC);
$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $gateway = new Gateway($db);

        $searchOptions = search_options(OWNERSHIP_ID, []);

        $count = $gateway->count($searchOptions);
        $res = $gateway->findAll($searchOptions, true);

        if ($res instanceof PDOException) database_error();
        else if ($res == null) success([]);
        else {
            $res = array_map(fn($itm) => array_merge($itm->jsonSerialize(), [LINK => api_link('/api/ownership/' . $itm->getId() . '/')]), $res);
            success_with_pagination($res, $count);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else wrong_method([GET]); 