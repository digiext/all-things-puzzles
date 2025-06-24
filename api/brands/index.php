<?php
global $db;

require "../../db.php";
$brandGateway = new puzzlethings\src\gateway\BrandGateway($db);

header("Content-Type: application/json");
echo json_encode($brandGateway->findAll());
die();