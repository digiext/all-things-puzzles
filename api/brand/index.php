<?php
use puzzlethings\src\gateway\BrandGateway;

$id = $_GET['id'] ?? null;
global $db;

require "../../db.php";
$brandGateway = new BrandGateway($db);

header("Content-Type: application/json");
$brand = $brandGateway->findById($id);


if ($brand == null) {
    http_response_code(403);
} else {
    header("Content-Type: application/json");
    echo json_encode($brand);
}
die();