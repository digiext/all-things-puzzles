<?php
use puzzlethings\src\gateway\DispositionGateway as Gateway;

global $db;

require __DIR__ . "/../../util/db.php";
$gateway = new Gateway($db);

$res = $gateway->findAll();

header("Content-Type: application/json");
echo json_encode($res);
die();