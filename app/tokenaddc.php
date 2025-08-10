<?php

use puzzlethings\src\gateway\APITokenGateway;

global $db;
require_once 'util/function.php';
require_once 'util/db.php';
require_once 'util/api_constants.php';

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

if (isset($_SESSION['blockRefresh'])) {
    header("Location: profile.php");
    return;
}

$_SESSION['blockRefresh'] = true;

$compoundPerms = array_map(fn ($itm) => PERM_LOOKUP[$itm], COMPOUND_PERMS);
$permLookupStrict = array_filter(PERM_LOOKUP, fn ($itm) => !in_array($itm, $compoundPerms));

$perm = 0;
foreach ($permLookupStrict as $int => $name) {
    if (array_key_exists($name, $_POST)) {
        $perm += $int;
    }
}

$name = $_POST['tokenname'];
$expire = $_POST['expire'];
try {
    $now = new DateTime('yesterday');
    $now->setTime(23, 59, 59);
    $oneYear = new DateTime()->modify('+1 year');
    $check = new DateTime($expire);
} catch (DateMalformedStringException $e) {
    failAlert("Invalid expiration time!", 'profile.php');
    return;
}

if (empty($name)) {
    failAlert("Invalid token name!", 'profile.php');
    return;
}

if ($check < $now) {
    failAlert('Token can not be already expired!', 'profile.php');
    return;
}

if ($check > $oneYear) {
    failAlert("Tokens can not live for longer than a year!", 'profile.php');
    return;
}

if ($perm == 0) {
    failAlert("You need to select at least one permission!", 'profile.php');
    return;
}

$token = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1, 16))), 1, 48);

$gateway = new APITokenGateway($db);
$gateway->create($name, getLoggedInUser(), $token, $perm, $expire);

$title = 'Generated Token';
include 'header.php';
include 'nav.php';
?>

<div class="container p-3 mt-4 mb-2">
    <h4>Your API Token has been generated!</h4>
    <hr>
    <div class="alert alert-danger">Copy it now, as you will never see it again!</div>

    <div class="col-auto input-group">
        <input type="password" class="form-control" id="token" disabled value="<?php echo $token ?>">
        <button class="btn btn-secondary" type="button" id="show"><i class="bi bi-eye" id="eye-icon"></i></button>
        <button class="btn btn-secondary" type="button" id="copyToClipboard"><i class="bi bi-clipboard" id="clipboard-icon"></i></button>
    </div>

    <br>
    <button onclick="window.location.href = 'profile.php'" class="btn btn-danger">Return to profile</button>
</div>

<script>
    $(function() {
        let token = $('#token');

        let eyeIcon = $('#eye-icon')
        $('#show').on('click', function() {
            if (token.attr('type') === 'password') {
                token.attr('type', 'text');
                eyeIcon.removeClass('bi-eye');
                eyeIcon.addClass('bi-eye-slash');
            } else {
                token.attr('type', 'password')
                eyeIcon.removeClass('bi-eye-slash');
                eyeIcon.addClass('bi-eye');
            }
        })

        let clipboardIcon = $('#clipboard-icon')
        $('#copyToClipboard').on('click', function() {
            navigator.clipboard.writeText(token.val())
            clipboardIcon.removeClass('bi-clipboard');
            clipboardIcon.addClass('bi-clipboard-check');
            setTimeout(() => {
                clipboardIcon.removeClass('bi-clipboard-check');
                clipboardIcon.addClass('bi-clipboard');
            }, 15000)
        })
    })
</script>