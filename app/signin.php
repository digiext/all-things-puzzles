<?php

use puzzlethings\src\gateway\AuthGateway;
use puzzlethings\src\gateway\UserGateway;

use puzzlethings\src\object\User;
use const puzzlethings\src\gateway\INVALID_USERNAME;
use const puzzlethings\src\gateway\USERNAME_IN_USE;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';

// Check if $_SESSION or $_COOKIE already set
if (isset($_SESSION[USER_ID])) {
    header("Location: " . (isset($_POST['from']) ? BASE_URL . "/" . $_POST['from'] : 'index.php'));
    exit;
} else if (isLoggedIn()) {
    // Decrypt cookie variable value
    $userid = decrypt($_SESSION[USER_ID]);

    // Fetch records
    $gateway = new UserGateway($db);
    $user = $gateway->findById($userid);

    if (!empty($user)) {
        $_SESSION[USER_ID] = $userid;
        header("Location: " . (isset($_POST['from']) ? BASE_URL . "/" .  $_POST['from'] : 'index.php'));
        exit;
    }
}

// On submit
if (isset($_POST['submit'])) {
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";
    $rememberme = boolval($_POST['rememberme']) ?? false;
    $from = $_POST['from'] ?? 'home.php';

    if ($username != "" && $password != "") {
        session_regenerate_id();

        $gateway = new UserGateway($db);
        $user = $gateway->attemptLogin($username, $password);

        if ($user instanceof User) {
            $_SESSION[USER_ID] = encrypt($user->getId());
            $_SESSION[USER_GROUP] = encrypt($user->getGroupId());

            if ($rememberme) {
                require_once __DIR__ . "/util/remember.php";

                $authGateway = new AuthGateway($db);
                remember($authGateway, $user);
            }

            successAlert("Welcome " . $username, $from);
        } else {
            failAlert("Incorrect username and password. Please try again.");
        }
    } else failAlert("Enter both Username and Password!", (isset($_POST['from']) ? BASE_URL . "/" . $_POST['from'] : 'index.php'));
}
