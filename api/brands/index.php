<?php
use puzzlethings\src\gateway\BrandGateway;

global $db;

require "../../db.php";
$brandGateway = new BrandGateway($db);

header("Content-Type: application/json");
echo json_encode($brandGateway->findAll());
die();