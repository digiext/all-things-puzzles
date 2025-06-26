<?php

use puzzlethings\src\gateway\UserGateway;

use puzzlethings\src\object\User;
use const puzzlethings\src\gateway\INVALID_USERNAME;
use const puzzlethings\src\gateway\USERNAME_IN_USE;

global $db;
require_once 'util/db.php';
require_once 'util/function.php';

// Check if $_SESSION or $_COOKIE already set
if (isset($_SESSION['userid'])) {
    header("Location: " . (isset($_POST['from']) ? $_SERVER['HTTPS'] ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_POST['from'] : 'index.php'));
    exit;
} else if (isLoggedIn()) {
    // Decrypt cookie variable value
    $userid = decryptCookie($_COOKIE[REMEMBER_ME]);

    // Fetch records
    $gateway = new UserGateway($db);
    $user = $gateway->findById($userid);

    if (!empty($user)) {
        $_SESSION['userid'] = $userid;
        header("Location: " . (isset($_POST['from']) ? $_SERVER['HTTPS'] ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_POST['from'] : 'index.php'));
        exit;
    }
}

// On submit
if (isset($_POST['submit'])) {
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";
    $from = $_POST['from'] ?? 'index.php';

    if ($username != "" && $password != "") {
        $gateway = new UserGateway($db);
        $user = $gateway->attemptLogin($username, $password);
        if ($user instanceof User) {
            $days = 30;
            $options = array(
                'expires' => time() + ($days * 24 * 60 * 60),
                'path' => '/'
            );

            if (isset($_POST['rememberme'])) {
                // Set cookie variables
                $value = encryptCookie($user->getId());
                setcookie(REMEMBER_ME, $value, $options);
            }

            setcookie(LOGGED_IN, encryptCookie("true"), $options);

            $group = $user->getGroupId();
            if ($group === ADMIN_GROUP_ID) setcookie("usg", encryptCookie(ADMIN_GROUP_ID), $options);

            session_start();
            successAlert("Welcome " . $username, "home.php");
        } else {
            failAlert("Incorrect username and password. Please try again.");
        }
    } else header("Location: " . (isset($_POST['from']) ? $_SERVER['HTTPS'] ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_POST['from'] : 'index.php'));
}
