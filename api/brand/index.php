<?php
use puzzlethings\src\gateway\BrandGateway as Gateway;

$id = $_GET['id'] ?? null;
global $db;

require __DIR__ . "/../../util/db.php";
$gateway = new Gateway($db);

$res = $gateway->findById($id);

if ($res == null) {
    http_response_code(404);
} else {
    header("Content-Type: application/json");
    echo json_encode($res);
}
die();