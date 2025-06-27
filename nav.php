<script src="<?php echo BASE_URL ?>/scripts/signup_validator.js"></script>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php
            $goto = isLoggedIn() ? "/home.php" : "/index.php";
            if (str_contains($_SERVER['REQUEST_URI'], "/" . $goto)) echo "#";
            else echo BASE_URL . $goto;
        ?>">
            <img src="<?php echo BASE_URL ?>/images/atp.png" alt="Logo" width="32" height="32">
            All Things Puzzles</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul>

            <div class="text-end">
                <!-- Sign In/Register Links Changes based on LoggedIn cookie -->
                <?php if (isLoggedIn()) {
                    echo "<div class='text-end'>";
                    echo "<a class='btn btn-outline-light m-1' type='submit' href='" . BASE_URL . "/profile.php'>Profile</a>";
                    echo "<a class='btn btn-warning' type='submit' href='" . BASE_URL . "/signout.php'>Logout</a>";
                    echo "</div>";
                } else {
                    echo "<div class='text-end'>";
                    echo "<button class='btn btn-outline-light m-1' type='submit' data-bs-toggle='modal' data-bs-target='#login'>Login</button>";
                    echo "<button class='btn btn-warning' type='submit' data-bs-toggle='modal' data-bs-target='#signup'>Sign Up</button>";
                    echo "</div>";
                } ?>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="loginLabel">Login</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="column" action="<?php echo BASE_URL ?>/signin.php" method="post" name="login">
                        <div class="modal-body">

                            <div class="col-auto">
                                <label for="usernameLogin" class="col-form-label">Username</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" class="form-control" id="usernameLogin" name="username">
                            </div>
                            <div class="col-auto">
                                <label for="passwordLogin" class="col-form-label">Password</label>
                            </div>
                            <div class="col-auto">
                                <input type="password" class="form-control" id="passwordLogin" name="password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" name="submit">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="signup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="signupLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="signupLabel">Sign Up</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="column needs-validation" action="<?php echo BASE_URL ?>/signup.php" method="post" id="signupForm" name="signup">
                        <div class="modal-body">
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="submitSignup" name="submit">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<?php
if (isset($_SESSION['success'])) {
    echo
    "<div class='alert alert-success alert-dismissible fade show' role='alert' id='successAlert'>
        <strong>" . $_SESSION['success'] . "</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";

    unset($_SESSION['success']);
} else if (isset($_SESSION['fail'])) {
    echo
        "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='failAlert'>
        <strong>" . $_SESSION['fail'] . "</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";

    unset($_SESSION['fail']);
}
?>

<script src="<?php echo BASE_URL ?>/scripts/util.js"></script>