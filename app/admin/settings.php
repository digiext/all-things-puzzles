<?php
global $db;
require_once '../util/function.php';
require_once '../util/constants.php';
require_once '../util/db.php';

$sql = "SELECT signup FROM settings";

$signup = $db->query($sql)->fetchColumn();

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Settings';
include '../header.php';
include '../nav.php';
?>

<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Settings</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="../admin.php">Admin Home</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>
<hr>
<div class="container mt-4 hstack gap-3">
    <div class="col-12">
        <form class="align-items-center" action="settingsc.php" method="post" id="form">
            <div class="mb-2 mx-1 hstack" id="signups">
                <label for="signups" class="form-label"><strong>Allow Sign Ups</strong></label>
                <div class="ms-3 form-check form-switch">
                    <?php
                    if ($signup == 0) {
                        echo "<input class='form-check-input' type='checkbox' id='signup' name='signup'>";
                    } elseif ($signup == 1) {
                        echo "<input class='form-check-input' type='checkbox' id='signup' name='signup' checked>";
                    }
                    ?>
                </div>
            </div>
            <input class="btn btn-success mt-3" type="submit" name="submit" id="submit">
        </form>
    </div>
</div>