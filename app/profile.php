<?php

use puzzlethings\src\gateway\APITokenGateway;
use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\UserGateway;
use puzzlethings\src\gateway\UserPuzzleGateway;
use puzzlethings\src\object\APIToken;
use puzzlethings\src\object\UserPuzzle;

global $db;
include 'util/api_constants.php';
include 'util/function.php';
include 'util/db.php';

if (isset($_SESSION['blockRefresh'])) {
    unset($_SESSION['blockRefresh']);
}

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Profile Page';
include 'header.php';
include 'nav.php';

$gateway = new UserGateway($db);
$ugateway = new UserPuzzleGateway($db);
$pgateway = new PuzzleGateway($db);
$user = getLoggedInUser();
$userid = getUserID();
$totaloptions = [
    FILTERS => [
        UINV_FILTER_USER => $userid
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

if ($totalpieces != 0) {
    $avgpieces = $totalpieces / $ugateway->userCountCompleted($userid);
} else {
    $avgpieces = 0;
}

$apitokengateway = new APITokenGateway($db);
$tokens = $apitokengateway->findByUser($user);

function perm_to_scopes(int $permsInt): string
{
    $perms = [];
    foreach (range(0, 31) as $bits) {
        if (($permsInt >> $bits) & 1) {
            $perms[] = PERM_LOOKUP[1 << $bits];
        }
    }

    if (count($perms) == (count(PERM_LOOKUP) - count(COMPOUND_PERMS))) {
        return 'all';
    }

    return join(', ', $perms);
}

function status(string $expiration): string
{
    $expire = DateTime::createFromFormat('Y-m-d', $expiration);
    $oneWeek = DateTime::createFromFormat('Y-m-d', $expiration)->sub(new DateInterval('P1W'));
    $now = new DateTime()->setTime(0, 0);

    if ($now < $oneWeek) {
        // Not Expired
        return "<span class='badge rounded-pill text-bg-success'><i class='bi bi-check'></i> Active</span>";
    } elseif ($now < $expire) {
        // One Week till Expiration
        return "<span class='badge rounded-pill text-bg-warning'><i class='bi bi-clock-history'></i> Expiring</span>";
    } else {
        // Expired
        return "<span class='badge rounded-pill text-bg-danger'><i class='bi bi-x'></i> Expired</span>";
    }
}

function relative_date(string $ts): string
{
    if(!ctype_digit($ts)) $ts = strtotime($ts);

    $diff = new DateTime()->setTime(0, 0)->getTimestamp() - $ts;
    if($diff == 0) return 'Today';
    else if($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        $month_diff = floor($diff / 2592000);
        if($day_diff == 0)
        {
            if($diff < 60) return 'Just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'Last month';
        if ($month_diff < 12) return $month_diff . ' months ago';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        $month_diff = floor($diff / 2592000);
        if($day_diff == 0)
        {
            if($diff < 120) return 'In a minute';
            if($diff < 3600) return 'In ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'In an hour';
            if($diff < 86400) return 'In ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'Next week';
        if(ceil($day_diff / 7) < 5) return 'In ' . ceil($day_diff / 7) . ' weeks';
        if(intval(date('n', $ts)) == intval(date('n')) + 1) return 'Next month';
        if($month_diff < 12) return 'In ' . $month_diff . ' months';
        return date('F Y', $ts);
    }
}
?>

<script src="scripts/profile_validator.js"></script>

<div class="container-fluid mb-2 mt-4 hstack justify-content-between">
    <h4 class="justify-content-start">Hello, <?php echo $user->getFullname() ?? $user->getUsername() ?></h4>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary justify-content-end" href="home.php">Home</a>
    </div>
</div>
<hr>
<div class="hstack gap-2 align-items-start">
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
        <div class="ms-2 col-6">
            <label for="dark"><strong>Color Theme</strong></label>
        </div>
        <div class="ms-2 mode-switch">
            <button title="Use dark mode" id="dark" class="btn btn-sm btn-default text-secondary">
                <i class="bi bi-moon"></i>
            </button>
            <button title="Use light mode" id="light" class="btn btn-sm btn-default text-secondary">
                <i class="bi bi-sun"></i>
            </button>
            <button title="Use system preferred mode" id="system" class="btn btn-sm btn-default text-secondary">
                <i class="bi bi-display"></i>
            </button>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-danger p-2 me-2 mx-1" type="submit" data-bs-toggle="modal" data-bs-target="#userdelete">Delete Account</button>
        </div>

    </div>



    <!-- Vertical Line between blocks -->
    <div class="vr d-none d-sm-block"></div>

    <!-- Right Side Info Block -->
    <div class="col d-none d-sm-block">
        <h4>User Stats</h4>
        <div><strong>Total Puzzles Owned:</strong> <?php echo $ugateway->count($totaloptions) ?></div>
        <div><strong>Puzzles Completed:</strong> <?php echo $ugateway->userCountCompleted($userid) ?></div>
        <div><strong>Last Completed Puzzle:</strong> <?php echo $lastpuzname ?></div>
        <div><strong>Total Pieces Done:</strong> <?php echo $totalpieces ?></div>
        <div><strong>Average Pieces Per Puzzle: </strong> <?php echo number_format($avgpieces, 2) ?></div>
        <hr>
        <div class="container mb-2 mt-4 pe-3 ps-0 hstack justify-content-between">
            <h4 class="text-center align-text-bottom">API Token Management</h4>
            <div class="d-grid gap-2 d-md-flex">
                <a class="btn btn-primary" href="tokenadd.php">Add New</a>
                <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
            </div>
        </div>
        <table
            id="table"
            data-classes="table table-bordered table-striped table-hover"
            data-toggle="table"
            data-pagination="true"
            data-search="false"
            data-buttons-toolbar=".buttons-toolbar"
            data-page-list="10,25,50,100,all"
            data-search-on-enter-key="false"
            data-id-field="id">
            <thead>
                <tr>
                    <th scope="col" class="text-center align-middle visually-hidden" data-field="id">ID</th>
                    <th scope="col" class="col-2 text-center align-middle" data-field="name">Name</th>
                    <th scope="col" class="col-1 text-center align-middle" data-field="status">Status</th>
                    <th scope="col" class="col-7 text-center align-middle" data-field="scopes">Scopes</th>
                    <th scope="col" class="col-2 text-center align-middle" data-field="expiration">Expiration</th>
                    <th scope="col" class="text-center">Delete</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach ($tokens as $token) {
                    if (!($token instanceof APIToken)) continue;
                    echo
                    "<tr>
                        <th scope='row' class='text-center align-middle id visually-hidden'>" . $token->getId() . "</th>
                        <td class='text-center align-middle status name'>" . $token->getName() . "</td>
                        <td class='text-center align-middle status'>" . status($token->getExpiration()) . "</td>
                        <td class='text-center align-middle scopes'>" . perm_to_scopes($token->getPermissions()) . "</td>
                        <td class='text-center align-middle expiration'><span><i class='bi bi-clock-history'></i> " . relative_date($token->getExpiration()) . "</span><br> <span>" . DateTime::createFromFormat('Y-m-d', $token->getExpiration())->format("F jS, Y") . "</span></td>
                        <td class='text-center'><button class='btn btn-secondary delete' type='submit' data-bs-toggle='modal' data-bs-target='#delete'><i class='bi bi-trash'></td>
                        </tr>";
                } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div class="modal fade" id="userdelete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userLabel">Delete Confirmation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="column" action="userdelete.php" method="post" name="userdelete">
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">Are you <strong>sure</strong> you want to delete your account?</div>

                    <div class="col-auto">
                        <label for="deleteId" class="col-form-label visually-hidden">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control id visually-hidden" id="userid" name="id" value="<?php echo $userid ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success" name="submit">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Token Confirmation Modal -->
<div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteLabel">Delete Confirmation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="column" action="tokendelete.php" method="post" name="token">
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">Are you <strong>sure</strong> you want to delete this token?</div>
                    <div class="col-auto">
                        <label for="deleteId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control id" id="deleteId" name="id" value="<?php echo empty($tokens) ? 0 : $tokens[0]->getId() ?? 0; ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="deleteToken" class="col-form-label">Token Name</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteToken" name="tokenname" value="<?php echo empty($tokens) ? 0 : $tokens[0]->getName() ?? 'null'; ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success" name="submit">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    $(function() {
        const table = $('#table');

        table.on('click', '.delete', function() {
            let row = $(this).closest('tr');
            let rowId = row.children('.id');
            let rowToken = row.children('.name');

            let modalId = $("#deleteId");
            let modalToken = $("#deleteToken");

            modalId.val(rowId.html())
            modalToken.val(rowToken.html())
        })
    })
</script>
<script src="<?php echo BASE_URL ?>/scripts/theme.js"></script>