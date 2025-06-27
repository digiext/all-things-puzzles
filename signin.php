<?php

use puzzlethings\src\gateway\UserGateway;

use puzzlethings\src\object\User;
use const puzzlethings\src\gateway\INVALID_USERNAME;
use const puzzlethings\src\gateway\USERNAME_IN_USE;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

// Check if $_SESSION or $_COOKIE already set
if (isset($_SESSION[USER_ID])) {
    header("Location: " . (isset($_POST['from']) ? BASE_URL . $_POST['from'] : 'index.php'));
    exit;
} else if (isLoggedIn()) {
    // Decrypt cookie variable value
    $userid = decrypt($_SESSION[USER_ID]);

    // Fetch records
    $gateway = new UserGateway($db);
    $user = $gateway->findById($userid);

    if (!empty($user)) {
        $_SESSION[USER_ID] = $userid;
        header("Location: " . (isset($_POST['from']) ? BASE_URL . $_POST['from'] : 'index.php'));
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

//            if (isset($_POST['rememberme'])) {
//                // Set cookie variables
//                $value = encrypt($user->getId());
//                setcookie(REMEMBER_ME, $value, $options);
//            }

            $_SESSION[USER_ID] = encrypt($user->getId());
            $_SESSION[USER_GROUP] = encrypt($user->getGroupId());

            successAlert("Welcome " . $username, "home.php");
        } else {
            failAlert("Incorrect username and password. Please try again.");
        }
    } else header("Location: " . (isset($_POST['from']) ? BASE_URL . $_POST['from'] : 'index.php'));
}
