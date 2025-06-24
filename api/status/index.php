<?php
use puzzlethings\src\gateway\StatusGateway as Gateway;

$id = $_GET['id'] ?? null;
global $db;

require "../../db.php";
$gateway = new Gateway($db);

$res = $gateway->findById($id);

if ($res == null) {
    http_response_code(404);
} else {
    header("Content-Type: application/json");
    echo json_encode($res);
}
die();