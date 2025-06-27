<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\SourceGateway;
use puzzlethings\src\object\Source;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Sources';
include '../header.php';
include '../nav.php';

$gateway = new SourceGateway($db);
$sources = $gateway->findAll();
?>

<script src="scripts/brands.js"></script>

<!--<div class="container mb-2 mt-5 hstack">-->
<!--    <h3 class="text-center">Brand Table</h3>-->
<!--    <div class="d-grid gap-2 d-md-flex ms-auto">-->
<!--        <a class="btn btn-primary me-md-2" href="../admin.php">Admin Home</a>-->
<!--        <a class="btn btn-warning" href="brandadd.php">Add New</a>-->
<!--    </div>-->
<!--</div>-->
<div class="container mb-2 mt-4">
    <h3 class="text-center">Source Table</h3>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-primary">ID Desc</a>
            <a class="btn btn-primary">ID Asc</a>
            <a class="btn btn-primary">Name Desc</a>
            <a class="btn btn-primary">Name Asc</a>
        </div>
        <div class="col-6 d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary me-md-2" href="../admin.php">Admin Home</a>
            <a class="btn btn-warning" href="sourcesadd.php">Add New</a>
        </div>
    </div>
</div>

<div class="container my-2">
    <table class="table table-bordered table-dark table-striped">
        <thead>
            <tr>
                <th scope="col" class="text-center align-middle">#</th>
                <th scope="col" class="col-11 align-middle">Source</th>
                <th scope="col" class="text-center">Edit</th>
                <th scope="col" class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($sources as $source) {
                if (!($source instanceof Source)) continue;
                echo
                "<tr class='brand-row'>
                    <th scope='row' class='text-center align-middle border-end id''>" . $source->getId() . "</th>
                    <td class='align-middle name'>" . $source->getSource() . "</td>
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
            <form class="column" action="sourcesdelete.php" method="post" name="login">
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">Are you <strong>sure</strong> you want to delete this brand?</div>
                    <div class="col-auto">
                        <label for="deleteId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteId" name="id" value="<?php echo $source->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="deleteBrand" class="col-form-label">Source</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteBrand" name="source" value="<?php echo $source->getSource(); ?>" readonly>
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
            <form class="column" action="sourcesedit.php" method="post" name="sourcesedit">
                <div class="modal-body">
                    <div class="col-auto">
                        <label for="editId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editId" name="id" value="<?php echo $source->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="editBrand" class="col-form-label">Source</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editBrand" name="source" value="<?php echo $source->getSource(); ?>">
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