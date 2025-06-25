<?php

use puzzlethings\src\gateway\UserGateway;

use const puzzlethings\src\gateway\INVALID_USERNAME;

require_once 'db.php';
require_once 'function.php';

try {
    $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM user");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "" . $e->getMessage() . "";
    //echo "<style>.hidden { visibility: visible; } .shown { visibility: hidden; font-size: 0px }</style>";
}
if (isset($_POST['submit'])) {
    $username = $_POST['userid'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $gateway = new UserGateway($conn);
    $code = $gateway->create($username, $fullname, $email, $password, false);

    if ($code == INVALID_USERNAME) {
        echo '<div class="alert alert-danger" role="alert>Invalid username!</div>';
    }
}
