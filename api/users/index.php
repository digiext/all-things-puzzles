<?php
use puzzlethings\src\gateway\UserGateway as Gateway;

global $db;
require "../../util/db.php";
$gateway = new Gateway($db);

$res = $gateway->findAll();

header("Content-Type: application/json");
echo json_encode($res);
die();