<?php
include 'util/function.php';

$title = 'All Things Puzzles';
include 'header.php';
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