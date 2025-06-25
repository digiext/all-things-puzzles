<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/atp.png" alt="Logo" width="30" height="24">
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
                    echo "<button class='btn btn-outline-light' type='submit' href='profile.php'>Profile</button>";
                    echo "<button class='btn btn-warning' type='submit' href='logout.php'>Logout</button>";
                    echo "</div>";
                } else {
                    echo "<div class='text-end'>";
                    echo "<button class='btn btn-outline-light' type='submit' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Login</button>";
                    echo "<button class='btn btn-warning' type='submit' href='register.php'>Sign Up</button>";
                    echo "</div>";
                } ?>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Login</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="column" action="signin.php" method="post" name="login">
                        <div class="modal-body">

                            <div class="col-auto">
                                <label for="username" class="col-form-label">User Name</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" class="form-control" id="username">
                            </div>
                            <div class="col-auto">
                                <label for="password" class="col-form-label">Password</label>
                            </div>
                            <div class="col-auto">
                                <input type="password" id="password" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>