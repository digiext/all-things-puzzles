<?php
global $db;
require_once 'util/function.php';
require_once 'util/constants.php';
require_once 'util/db.php';
require_once 'util/api_constants.php';



//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: index.php");
}

$title = 'Add New API Token';
include 'header.php';
include 'nav.php';

?>

<div class="container mb-2 mt-4 hstack gap-3">
    <div class="col-12">
        <form class="align-items-center" action="tokenaddc.php" method="post" id="form">
            <div class="p-2 mb-2 mx-1" id="dname">
                <label for="tokenname" class="form-label"><strong>Token Name</strong></label>
                <input type="text" class="form-control" name="tokenname" id="tokenname">
                <div id="nameFeedback"></div>
            </div>

            <div class="p-2 mb-2 mx-1" id="dexpire">
                <label for="expire" class="form-label"><strong>Expiration Date</strong></label>
                <input type="date" class="form-control" name="expire" id="expire">
            </div>

            <div class="p-2 mb-2 mx-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="read_profile" name="read_profile">
                    <label class="form-check-label" for="read_profile">
                        <div>
                            <span>read_profile</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read-only access to your profile, including username, full name, and last login</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="read_puzzle" name="read_puzzle">
                    <label class="form-check-label" for="read_puzzle">
                        <div>
                            <span>read_puzzle</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read-only access to read puzzle information</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="read_wishlist" name="read_wishlist">
                    <label class="form-check-label" for="read_wishlist">
                        <div>
                            <span>read_wishlist</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read-only access to your wishlist</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="read_user_inventory" name="read_user_inventory">
                    <label class="form-check-label" for="read_user_inventory">
                        <div>
                            <span>read_user_inventory</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read-only access to your user inventory</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="read" name="read">
                    <label class="form-check-label" for="read">
                        <div>
                            <span>read</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read-only access to the API</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="write_profile" name="write_profile">
                    <label class="form-check-label" for="write_profile">
                        <div>
                            <span>write_profile</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants write access to your profile's information</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="profile" name="profile">
                    <label class="form-check-label" for="profile">
                        <div>
                            <span>profile</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read and write access to your profile's information</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="create_puzzle" name="create_puzzle">
                    <label class="form-check-label" for="create_puzzle">
                        <div>
                            <span>create_puzzle</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to create a puzzle</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="edit_puzzle" name="edit_puzzle">
                    <label class="form-check-label" for="edit_puzzle">
                        <div>
                            <span>edit_puzzle</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to edit any puzzle</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="delete_puzzle" name="delete_puzzle">
                    <label class="form-check-label" for="delete_puzzle">
                        <div>
                            <span>delete_puzzle</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to delete any puzzle</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="write_puzzle" name="write_puzzle">
                    <label class="form-check-label" for="write_puzzle">
                        <div>
                            <span>write_puzzle</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants create, edit, and delete access to any puzzle</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="puzzle" name="puzzle">
                    <label class="form-check-label" for="puzzle">
                        <div>
                            <span>puzzle</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read, create, edit, and delete access to any puzzle</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="create_wishlist" name="create_wishlist">
                    <label class="form-check-label" for="create_wishlist">
                        <div>
                            <span>create_wishlist</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to create a puzzle on your wishlist</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="edit_wishlist" name="edit_wishlist">
                    <label class="form-check-label" for="edit_wishlist">
                        <div>
                            <span>edit_wishlist</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to edit a puzzle on your wishlist</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="delete_wishlist" name="delete_wishlist">
                    <label class="form-check-label" for="delete_wishlist">
                        <div>
                            <span>delete_wishlist</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to delete a puzzle on your wishlist</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="write_wishlist" name="write_wishlist">
                    <label class="form-check-label" for="write_wishlist">
                        <div>
                            <span>write_wishlist</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants create, edit, and delete access to your wishlist</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="wishlist" name="wishlist">
                    <label class="form-check-label" for="wishlist">
                        <div>
                            <span>wishlist</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read, create, edit, and delete access to your wishlist</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="add_user_inventory" name="add_user_inventory">
                    <label class="form-check-label" for="add_user_inventory">
                        <div>
                            <span>add_user_inventory</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to add a puzzle to your user inventory</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="edit_user_inventory" name="edit_user_inventory">
                    <label class="form-check-label" for="edit_user_inventory">
                        <div>
                            <span>edit_user_inventory</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to edit a puzzle in your user inventory</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="remove_user_inventory" name="remove_user_inventory">
                    <label class="form-check-label" for="remove_user_inventory">
                        <div>
                            <span>remove_user_inventory</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants the ability to remove a puzzle from your user inventory</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="write_user_inventory" name="write_user_inventory">
                    <label class="form-check-label" for="write_user_inventory">
                        <div>
                            <span>write_user_inventory</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants add, edit, and delete access to your user inventory</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="user_inventory" name="user_inventory">
                    <label class="form-check-label" for="user_inventory">
                        <div>
                            <span>user_inventory</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants read, add, edit, and delete access to your user inventory</span>
                        </div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="write" name="write">
                    <label class="form-check-label" for="write">
                        <div>
                            <span>write</span>
                            <br>
                            <span class="text-body-secondary p-0">Grants full read and write access to the API</span>
                        </div>
                    </label>
                </div>
            </div>
            <input class="btn btn-success" type="submit" name="submit">
        </form>
    </div>