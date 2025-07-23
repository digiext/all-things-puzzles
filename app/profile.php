<?php

use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\UserGateway;
use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\UserPuzzle;


global $db;
include 'util/function.php';
include 'util/db.php';

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Home Page';
include 'header.php';
include 'nav.php';

$gateway = new UserGateway($db);
$ugateway = new UserPuzzleGateway($db);
$pgateway = new PuzzleGateway($db);
$user = getLoggedInUser();
$userid = getUserID();
$totaloptions = [
    FILTERS => [
        USR_FILTER_USER => $userid
    ]
];

$lastcomplete = $ugateway->userLastCompleted($userid);
if (!empty($lastcomplete)) {
    $lastpuzname = $pgateway->findById($lastcomplete)->getName();
} else {
    $lastpuzname = 'Nothing';
}

$totalpieces = 0;
$puzcomplete = $ugateway->userCompleted($userid);

foreach ($puzcomplete as $puzzle) {
    if (!($puzzle instanceof UserPuzzle)) continue;
    $totalpieces = $totalpieces + $puzzle->getPuzzle()->getPieces();
}

?>

<script src="scripts/profile_validator.js"></script>

<div class="container mt-4 mb-2">
    <h4>Hello, <?php echo $user->getFullname() ?? $user->getUsername() ?></h4>
    <hr>
    <div class="hstack gap-2">
        <div class="col-md-4 col-sm-12">
            <form class="p-2 mb-2 mx-1 align-items-center" action="useredit.php?ctx=uname" method="post">
                <div class="col-6">
                    <label for="updateUsername"><strong>Username</strong></label>
                </div>
                <div class="col-12 input-group">
                    <span class="input-group-text" id="usernameAddon">@</span>
                    <input type="text" class="form-control" id="updateUsername" name="username" value="<?php echo $user->getUsername() ?>">
                    <button class="btn btn-outline-success rounded-end" type="submit" id="updateUsernameSubmit" disabled>Update</button>
                    <div id="usernameFeedback"></div>
                </div>
            </form>
            <form class="p-2 my-2 mx-1 align-items-center" action="useredit.php?ctx=dname" method="post">
                <div class="col-6">
                    <label for="updateFullname"><strong>Display Name</strong></label>
                </div>
                <div class="col-12 input-group">
                    <input type="text" class="form-control" id="updateFullname" name="fullname" value="<?php echo $user->getFullname() ?>">
                    <button class="btn btn-outline-success rounded-end" type="submit" id="updateFullnameSubmit" disabled>Update</button>
                    <div id="fullnameFeedback"></div>
                </div>
            </form>
            <form class="p-2 my-2 mx-1 align-items-center" action="useredit.php?ctx=email" method="post">
                <div class="col-6">
                    <label for="updateEmail"><strong>Email</strong></label>
                </div>
                <div class="col-12 input-group">
                    <input type="email" class="form-control" id="updateEmail" name="email" value="<?php echo $user->getEmail() ?>">
                    <button class="btn btn-outline-success rounded-end" type="submit" id="updateEmailSubmit" disabled>Update</button>
                    <div id="emailFeedback"></div>
                </div>
            </form>
            <form class="p-2 my-2 mx-1 align-items-center" action="useredit.php?ctx=pword" method="post">
                <div class="col-6">
                    <label for="updatePassword"><strong>Password</strong></label>
                </div>
                <div class="col-12 input-group">
                    <input type="password" class="form-control" id="updatePassword" name="password" value="">
                    <button class="btn btn-outline-success rounded-end" type="submit" id="updatePasswordSubmit" disabled>Update</button>
                    <div id="passwordFeedback"></div>
                </div>
            </form>
        </div>
        <div class="vr d-none d-sm-block"></div>

        <div class="col d-none d-sm-block">
            <div><strong>Total Puzzles Owned:</strong> <?php echo $ugateway->count($totaloptions) ?></div>
            <div><strong>Puzzles Completed:</strong> <?php echo $ugateway->userCountCompleted($userid) ?></div>
            <div><strong>Last Completed Puzzle:</strong> <?php echo $lastpuzname ?></div>
            <div><strong>Total Pieces Done:</strong> <?php echo $totalpieces ?></div>
            <div><strong>Average Pieces Per Puzzle: </strong> <?php echo $totalpieces / $ugateway->userCountCompleted($userid) ?></div>
        </div>



    </div>
</div>