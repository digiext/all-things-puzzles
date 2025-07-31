<?php

use puzzlethings\src\gateway\UserGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

if (!isLoggedIn()) {
    header("Location: index.php");
}

$id = $_POST['id'];

$gateway = new UserGateway($db);
$code = $gateway->delete($id);

// session_start();
if (!$code) {
    failAlert("Error while deleting user!");
} else {
    if (isset($_COOKIE[COOKIE_REMEMBER_ME])) {
        unset($_COOKIE[COOKIE_REMEMBER_ME]);
        setcookie(COOKIE_REMEMBER_ME, '', -1);
    }

    unset($_SESSION[SESS_USER_ID]);
    unset($_SESSION[GROUP_ID_MEMBER]);
    unset($_SESSION[SESS_USER_NAME]);

    session_destroy();
    successAlert("User (ID $id) has been deleted");
}

header("Location: index.php");
