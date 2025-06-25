<?php
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
    echo "<style>.hidden { visibility: visible; } .shown { visibility: hidden; font-size: 0px }</style>";
}
if (isset($_POST['submit'])) {
    try {
        $userid = $_POST['userid'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $useridf = htmlspecialchars($userid);
        if ($useridf == "") die("Unable to validate username input");
        $userid = $useridf;

        $emailf = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($emailf === false) die("Unable to validate email input");
        $email = $mailf;

        $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT user_name FROM user WHERE user_name = :userid");
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            die('<p class="mainClass">Username already in use!</p>');
        } else {
            $stmt = $conn->prepare("SELECT email FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                die('<p class="mainClass">Email already in use!</p>');
            } else {
                $sql = "INSERT INTO `user` (`user_name`,`full_name`, `email`, `user_password`, `emailconfirmed`, `user_hash`,`themeid`,`usergroupid`) VALUES (?, ?, ?, ?, ?, ?, (SELECT themeid FROM theme WHERE themedesc = 'Default'), (SELECT usergroupid FROM usergroup WHERE groupdesc = 'Admin'))";
                $hash = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1, 10))), 1, 32);
                $stmt = $conn->prepare($sql)->execute([$userid, $fullname, $email, $password, 0, $hash]);
                header("Location: index.php");
            }
        }
    } catch (PDOException $e) {
        echo "<style>.hidden { visibility: visible; } .shown { visibility: hidden; font-size: 0px }</style>";
    }
}
