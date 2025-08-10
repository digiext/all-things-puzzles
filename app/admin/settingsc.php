<?php

global $db;
require_once '../util/function.php';
require_once '../util/db.php';

if (isset($_POST['submit'])) {

    var_dump($_POST['signup']);

    if (!empty($_POST['signup'])) {
        $signup = 1;
    } else {
        $signup = 0;
    }

    echo ($signup);

    $sql = "UPDATE settings SET signup = :signup";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':signup', $signup, PDO::PARAM_INT);
    $stmt->execute();




    // session_start();
    if ($stmt == false) {
        failAlert("Setting not updated!");
    } else {
        successAlert("Setting has been changed");
    }

    header("Location: settings.php");
}
