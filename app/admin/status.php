<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\object\Status;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

if (!isAdmin()) {
    header("Location: ../admin.php");
}

$title = 'Status';
include '../header.php';
include '../nav.php';

$gateway = new StatusGateway($db);
$statuses = $gateway->findAll([
    MAX_PER_PAGE => 9999
]);
?>

<script src="scripts/status.js"></script>

<!--<div class="container mb-2 mt-5 hstack">-->
<!--    <h3 class="text-center">Brand Table</h3>-->
<!--    <div class="d-grid gap-2 d-md-flex ms-auto">-->
<!--        <a class="btn btn-primary me-md-2" href="../admin.php">Admin Home</a>-->
<!--        <a class="btn btn-warning" href="brandadd.php">Add New</a>-->
<!--    </div>-->
<!--</div>-->
<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Status Table</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="../admin.php">Admin Home</a>
        <a class="btn btn-warning" href="statusadd.php">Add New</a>
        <div class="row buttons-toolbar d-grid gap-2 d-md-flex"></div>
    </div>
</div>

<div class="container my-2">
    <table
        id="table"
        data-classes="table  table-bordered table-striped table-hover"
        data-toggle="table"
        data-pagination="true"
        data-search="true"
        data-buttons-toolbar=".buttons-toolbar"
        data-page-list="10,25,50,100,all"
        data-search-on-enter-key="false"
        data-id-field="id">
        <thead>
            <tr>
                <th scope="col" class="text-center align-middle" data-sortable="true" data-field="id">ID</th>
                <th scope="col" class="col-11 align-middle" data-sortable="true">Status</th>
                <th scope="col" class="text-center">Edit</th>
                <th scope="col" class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($statuses as $status) {
                if (!($status instanceof Status)) continue;
                echo
                "<tr class='brand-row'>
                    <th scope='row' class='text-center align-middle id''>" . $status->getId() . "</th>
                    <td class='align-middle name'>" . $status->getDescription() . "</td>
                    <td class='text-center'><button class='btn btn-secondary edit' type='submit' data-bs-toggle='modal' data-bs-target='#edit'><i class='bi bi-pencil'></td>
                    <td class='text-center'><button class='btn btn-secondary delete' type='submit' data-bs-toggle='modal' data-bs-target='#delete'><i class='bi bi-trash'></td>
                </tr>";
            } ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteLabel">Delete Confirmation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="column" action="statusdelete.php" method="post" name="login">
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">Are you <strong>sure</strong> you want to delete this status?</div>
                    <div class="col-auto">
                        <label for="deleteId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteId" name="id" value="<?php echo $status->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="deleteStatus" class="col-form-label">Status</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteStatus" name="status" value="<?php echo $status->getDescription(); ?>" readonly>
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

<!-- Edit Modal -->
<div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editLabel">Edit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="column" action="statusedit.php" method="post" name="statusedit">
                <div class="modal-body">
                    <div class="col-auto">
                        <label for="editId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editId" name="id" value="<?php echo $status->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="editStatus" class="col-form-label">Status</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editStatus" name="status" value="<?php echo $status->getDescription(); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="editSubmit" name="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>