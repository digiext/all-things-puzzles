<?php

use puzzlethings\src\gateway\UserGateway;

use const puzzlethings\src\gateway\INVALID_USERNAME;
use const puzzlethings\src\gateway\USERNAME_IN_USE;

global $db;
require_once 'db.php';
require_once 'function.php';

// Check if $_SESSION or $_COOKIE already set
if (isset($_SESSION['userid'])) {
    header("Location: " . (isset($_POST['from']) ? $_SERVER['HTTPS'] ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_POST['from'] : 'index.php'));
    exit;
} else if (isLoggedIn()) {

    // Decrypt cookie variable value
    $userid = decryptCookie($_COOKIE['rememberme']);

    // Fetch records
    $gateway = new UserGateway($db);
    $code = $gateway->findById($userid);

    if (!empty($code)) {
        $_SESSION['userid'] = $userid;
        header("Location: " . (isset($_POST['from']) ? $_SERVER['HTTPS'] ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_POST['from'] : 'index.php'));
        exit;
    }
}

// On submit
if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $from = $_POST['from'] ?? 'index.php';

    if ($username != "" && $password != "") {

        $gateway = new UserGateway($db);
        $code = $gateway->validLogin($username, $password, true);
        if ($code == true) {
            if (isset($_POST['rememberme'])) {
                // Set cookie variables
                $value = encryptCookie($userid);

                $days = 30;
                $options = array(
                    'expires' => time() + ($days * 24 * 60 * 60),
                    'path' => '/'
                );
                setcookie("rememberme", $value, $options);
                setcookie("loggedin", encryptCookie("true"), $options);
            } else {
                // Set cookie variables
                $days = 30;
                $options = array(
                    'expires' => time() + ($days * 24 * 60 * 60),
                    'path' => '/'
                );
                setcookie("loggedin", encryptCookie("true"), $options);
            }

            $code = $gateway->findByName($username);
            $admin = $code['usergroupid'];

            if ($admin == 1) {
                $days = 30;
                $options = array(
                    'expires' => time() + ($days * 24 * 60 * 60),
                    'path' => '/'
                );
                setcookie("usg", encryptCookie("admin"), $options);
            }

            header("Location: $from");

            exit;
        } else {
            session_start();
            $_SESSION['fail'] = "Incorrect username and password.  Please try again";
            header("Location: index.php");
        }
    }
}
