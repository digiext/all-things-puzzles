<?php
use puzzlethings\src\gateway\PuzzleGateway as Gateway;

$maxperpage = $_GET['maxperpage'] ?? 10;
$page = $_GET['page'] ?? 0;
$name = $_GET['name'] ?? null;


$options = [
    "page" => $page,
    "maxperpage" => $maxperpage,
];

global $db;

require "../../db.php";
$gateway = new Gateway($db);

$res = $gateway->findAll($options);

if (count($res) < $maxperpage) {
    $next = null;
} else {
    $query = [];
    parse_str($_SERVER['QUERY_STRING'], $query);
    $query['page'] = $page + 1;
    $next = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?" . http_build_query($query);
}


header("Content-Type: application/json");
echo json_encode([
    "puzzles" => $res,
    "next" => $next,
]);
die();