<?php

use puzzlethings\src\gateway\APITokenGateway;


global $db;
require_once 'util/function.php';
require_once 'util/db.php';

$id = $_POST['id'];

$gateway = new APITokenGateway($db);
$code = $gateway->delete($id);

// session_start();
if (!$code) {
    failAlert("Error while deleting token!");
} else {
    successAlert("Token (ID $id) has been deleted");
}

header("Location: profile.php");
