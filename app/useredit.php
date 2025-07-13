<?php

global $db;
;
use puzzlethings\src\gateway\UserGateway;
use puzzlethings\src\object\User;

require_once 'util/function.php';
require_once 'util/db.php';

$ctx = $_GET['ctx'];
$uid = getUserID();
$gateway = new UserGateway($db);

switch ($ctx) {
    case 'uname': {
        //username
        $success = $gateway->updateUsername($uid, $_POST['username']) instanceof User;
        if ($success) successAlert("Updated username!", "profile.php");
        else failAlert("Failed to update username!", "profile.php");
        break;
    }
    case 'dname': {
        //fullname
        $success = $gateway->updateFullName($uid, $_POST['fullname']);
        if ($success) successAlert("Updated display name!", "profile.php");
        else failAlert("Failed to update display name!", "profile.php");
        break;
    }
    case 'email': {
        //email
        $success = $gateway->updateEmail($uid, $_POST['email']);
        if ($success) successAlert("Updated email!", "profile.php");
        else failAlert("Failed to update email!", "profile.php");
        break;
    }
    case 'pword': {
        //password
        $success = $gateway->updatePassword($uid, $_POST['password']);

        if ($success) successAlert("Updated password!", "profile.php");
        else failAlert("Failed to update password!", "profile.php");
        break;
    }
}

