<?php
use puzzlethings\src\gateway\PuzzleGateway as Gateway;

$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? null;
$before = $_GET['before'] ?? null;
$after = $_GET['after'] ?? null;
$pieces = $_GET['pieces'] ?? null;

try {
    if ($before != null) $before = new DateTime($before);
} catch (Exception $e) {
}

try {
    if ($after != null) $after = new DateTime($after);
} catch (Exception $e) {
}

$options = [
    "pieces" => $pieces,
    "before" => $before,
    "after" => $after,
];

global $db;

require "../../db.php";
$gateway = new Gateway($db);

$res = null;
if ($id != null) {
    $res = $gateway->findById($id, $options);
} else if ($name != null) {
    $res = $gateway->findByName($name, $options);
}

if ($res == null) {
    http_response_code(404);
} else {
    header("Content-Type: application/json");
    echo json_encode($res);
}
die();