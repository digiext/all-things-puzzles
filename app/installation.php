<?php
global $db;
include 'util/function.php';
include 'util/db.php';


$sql = "SELECT installed FROM setup";

$setup = $db->query($sql)->fetchColumn();

if ($setup == 1) {
    header("Location: index.php");
}

$title = 'All Things Puzzles';
include 'header.php';
?>

<?php
if (isset($_SESSION['success'])) {
    echo
    "<div class='alert alert-success alert-dismissible fade show' role='alert' id='successAlert'>
        <strong>" . $_SESSION['success'] . "</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";

    unset($_SESSION['success']);
}

if (isset($_SESSION['warning'])) {
    echo
    "<div class='alert alert-warning alert-dismissible fade show' role='alert' id='warnAlert'>
        <strong>" . $_SESSION['warning'] . "</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";

    unset($_SESSION['warning']);
}

if (isset($_SESSION['fail'])) {
    echo
    "<div class='alert alert-danger alert-dismissible fade show' role='alert' id='failAlert'>
        <strong>" . $_SESSION['fail'] . "</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";

    unset($_SESSION['fail']);
}
?>

<div class="container">
    <h3>Fill in form below for initial user creation</h3>
    <form class="row" action="installupd.php" method="post" name="installupd">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>

        <div class="mb-3">
            <label for="fullname" class="form-label">Display Name</label>
            <input type="text" class="form-control" name="fullname" id="fullname" placeholder="First Name Last Name">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" aria-describedby="passwordHelpBlock">
            <div id="passwordHelpBlock" class="form-text">
                Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
        </div>
    </form>
</div>