<?php

use puzzlethings\src\gateway\AuthGateway;

global $db;
require_once __DIR__ . "/util/function.php";
require_once __DIR__ . "/util/db.php";

$gateway = new AuthGateway($db);
$gateway->deleteToken(getUserID());

if (isset($_COOKIE[COOKIE_REMEMBER_ME])) {
    unset($_COOKIE[COOKIE_REMEMBER_ME]);
    setcookie(COOKIE_REMEMBER_ME, '', -1);
}

unset($_SESSION[SESS_USER_ID]);
unset($_SESSION[GROUP_ID_MEMBER]);
unset($_SESSION[SESS_USER_NAME]);

session_destroy();

header("Location: index.php");
