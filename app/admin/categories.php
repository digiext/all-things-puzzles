<?php
global $db;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\CategoryGateway;
use puzzlethings\src\object\Category;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Category: ../home.php");
}

$title = 'Categories';
include '../header.php';
include '../nav.php';

$gateway = new CategoryGateway($db);
$categories = $gateway->findAll([
        SORT => CATEGORY_ID,
        MAX_PER_PAGE => 9999
]) ?? [];
?>

<script src="scripts/categories.js"></script>

<!--<div class="container mb-2 mt-5 hstack">-->
<!--    <h3 class="text-center">Brand Table</h3>-->
<!--    <div class="d-grid gap-2 d-md-flex ms-auto">-->
<!--        <a class="btn btn-primary me-md-2" href="../admin.php">Admin Home</a>-->
<!--        <a class="btn btn-warning" href="brandadd.php">Add New</a>-->
<!--    </div>-->
<!--</div>-->
<div class="container mb-2 mt-4 hstack justify-content-between">
    <h3 class="text-center align-text-bottom">Category Table</h3>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary" href="../admin.php">Admin Home</a>
        <a class="btn btn-warning" href="categoriesadd.php">Add New</a>
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
                <th scope="col" class="col-11 align-middle" data-sortable="true">Category</th>
                <th scope="col" class="text-center">Edit</th>
                <th scope="col" class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($categories as $category) {
                if (!($category instanceof Category)) continue;
                echo
                "<tr class='brand-row'>
                    <th scope='row' class='text-center align-middle id''>" . $category->getId() . "</th>
                    <td class='align-middle name'>" . $category->getDescription() . "</td>
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
            <form class="column" action="categoriesdelete.php" method="post" name="login">
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">Are you <strong>sure</strong> you want to delete this brand?</div>
                    <div class="col-auto">
                        <label for="deleteId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteId" name="id" value="<?php echo $category->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="deleteCategory" class="col-form-label">Category</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="deleteCategory" name="category" value="<?php echo $category->getDescription(); ?>" readonly>
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
            <form class="column" action="categoriesedit.php" method="post" name="categoriesedit">
                <div class="modal-body">
                    <div class="col-auto">
                        <label for="editId" class="col-form-label">ID</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editId" name="id" value="<?php echo $category->getId(); ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="editCategory" class="col-form-label">Category</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="editCategory" name="category" value="<?php echo $category->getDescription(); ?>">
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