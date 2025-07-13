<?php
use puzzlethings\src\gateway\AuthGateway;

global $db;
require_once __DIR__ . "/util/function.php";
require_once __DIR__ . "/util/db.php";

$gateway = new AuthGateway($db);
$gateway->deleteToken(getUserID());

if (isset($_COOKIE[REMEMBER_ME])) {
    unset($_COOKIE[REMEMBER_ME]);
    setcookie(REMEMBER_ME, null, -1);
}

unset($_SESSION[USER_ID]);
unset($_SESSION[USER_GROUP_ID]);
unset($_SESSION[USER_NAME]);

session_destroy();

header("Location: index.php");
