<?php
include 'util/function.php';

//If Not Logged In Reroute to index.php
if (isLoggedIn()) {
    header("Location: home.php");
}

$title = 'All Things Puzzles';
include 'header.php';
include 'nav.php';

?>
<?php
if (isset($_SESSION['success'])) { ?>
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        <h4><?php
            echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div><?php
        } elseif (isset($_SESSION['fail'])) { ?>
    <div class='alert alert-danger alert-dismissible fade show' role="alert">
        <h4><?php
            echo $_SESSION['fail'];
            unset($_SESSION['fail']); ?>
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div><?php
        }
            ?>

<div class="container-fluid">
    <br>
    <h1>Welcome to All Things Puzzles!</h1>
    <br>
    <h4>All Things Puzzles is a inventory management system of your personal puzzle collection. </h4>
    <br>
    <h3>Features
        <ul>
            <li>Master Puzzle List</li>
            <li>Multi-user capable</li>
            <li>Wishlist</li>
        </ul>
    </h3>
    <br>
    <h3>To get started, sign up for an account using the Sign Up button in the upper right corner.</h3>
</div>