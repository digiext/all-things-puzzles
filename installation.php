<?php
include 'function.php';

$title = 'All Things Puzzles';
include 'header.php';
?>

<div class="container">
    <h3>Fill in form below for initial user creation</h3>
    <form class="row" action="installupd.php" method="post" name="installupd">
        <div class="mb-3">
            <label for="userid" class="form-label">User ID</label>
            <input type="text" class="form-control" name="userid">
        </div>

        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="fullname">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" placeholder="name@example.com">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" aria-describedby="passwordHelpBlock">
            <div id="passwordHelpBlock" class="form-text">
                Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3" name="submit">Submit</button>
        </div>
    </form>
</div>