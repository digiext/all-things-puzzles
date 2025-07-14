<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\UserGateway;
use puzzlethings\src\object\User;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Add User';
include '../header.php';
include '../nav.php';


?>

<div class="container">
    <h3>Fill in form below for adding a user</h3>
    <form class="column needs-validation" action="<?php echo BASE_URL ?>/../signup.php" method="post" id="signupForm" name="signup">

        <div class="col-auto">
            <label for="usernameSignup" class="col-form-label">Username</label>
        </div>
        <div class="col-auto input-group">
            <span class="input-group-text" id="usernameAddon">@</span>
            <input type="text" class="form-control rounded-end" id="usernameSignup" name="username" required>
            <div id="usernameSignupFeedback"></div>
        </div>
        <div class="col-auto">
            <label for="fullnameSignup" class="col-form-label">Display Name</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="fullnameSignup" name="fullname">
        </div>
        <div class="col-auto">
            <label for="emailSignup" class="col-form-label">Email</label>
        </div>
        <div class="col-auto">
            <input type="email" class="form-control" id="emailSignup" name="email" placeholder="name@example.com">
            <div id="emailSignupFeedback"></div>
        </div>
        <div class="col-auto">
            <label for="passwordSignup" class="col-form-label">Password</label>
        </div>
        <div class="col-auto">
            <input type="password" class="form-control" id="passwordSignup" name="password" required>
            <div id="passwordSignupFeedback"></div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success" id="submitSignup" name="submit">Sign Up</button>
            <a class="btn btn-danger" name="cancel" href="users.php">Cancel</a>
        </div>
    </form>
</div>