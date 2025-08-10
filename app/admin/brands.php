<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\object\Brand;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

$title = 'Brands';
include '../header.php';
include '../nav.php';

$gateway = new BrandGateway($db);
$brands = $gateway->findAll([
        MAX_PER_PAGE => 9999
]);
?>

<script src="scripts/brands.js"></script>

<!--<div class="container mb-2 mt-5 hstack">-->
<!--    <h3 class="text-center">Brand Table</h3>-->
<!--    <div class="d-grid gap-2 d-md-flex ms-auto">-->
<!--        <a class="btn btn-primary me-md-2" href="../admin.php">Admin Home</a>-->
<!--        <a class="btn btn-warning" href="brandadd.php">Add New</a>-->
<!--    </div>-->
<!--</div>-->
<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Brand Table</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="../admin.php">Admin Home</a>
        <a class="btn btn-warning" href="brandadd.php">Add New</a>
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
        data-id-field="id"
    >
        <thead>
            <tr>
                <th scope="col" class="text-center align-middle" data-sortable="true" data-field="id">ID</th>
                <th scope="col" class="col-11 align-middle" data-sortable="true" data-field="brand">Brand</th>
                <th scope="col" class="text-center">Edit</th>
                <th scope="col" class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($brands as $brand) {
                if (!($brand instanceof Brand)) continue;
                echo
                "<tr class='brand-row'>
                    <th scope='row' class='text-center align-middle id''>" . $brand->getId() . "</th>
                    <td class='align-middle name'>" . $brand->getName() . "</td>
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
            <form class="column" action="branddelete.php" method="post" name="login">
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">Are you <strong>sure</strong> you want to delete this brand?</div>
                    <div class="col-auto">
                        <label for="deleteId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteId" name="id" value="<?php echo $brands[0]->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="deleteBrand" class="col-form-label">Brand</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteBrand" name="brand" value="<?php echo $brands[0]->getName(); ?>" readonly>
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
            <form class="column" action="brandedit.php" method="post" name="brandedit">
                <div class="modal-body">
                    <div class="col-auto">
                        <label for="editId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editId" name="id" value="<?php echo $brand->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="editBrand" class="col-form-label">Brand</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editBrand" name="brand" value="<?php echo $brand->getName(); ?>">
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