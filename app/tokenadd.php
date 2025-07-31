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

$now = date('Y-m-d');
$oneMonth = new DateTime()->add(new DateInterval('P30D'))->format('Y-m-d');
$oneYear = new DateTime()->add(new DateInterval('P1Y'))->format('Y-m-d');
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
                <input type="date" class="form-control" name="expire" id="expire" value="<?php echo $oneMonth; ?>" min="<?php echo $now; ?>" max="<?php echo $oneYear; ?>">
            </div>

            <div class="p-2 mb-2 mx-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="profile" name="profile">
                    <label class="form-check-label" for="profile">
                        <span>profile</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read and write access to your profile</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="read_profile" name="read_profile">
                    <label class="form-check-label" for="read_profile">
                        <span>read_profile</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants full read-only access to your profile, including username, display name, and email</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="write_profile" name="write_profile">
                    <label class="form-check-label" for="write_profile">
                        <span>write_profile</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants write access to your profile's information</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="puzzle" name="puzzle">
                    <label class="form-check-label" for="puzzle">
                        <span>puzzle</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read, create, edit, and delete access to any puzzle</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="read_puzzle" name="read_puzzle">
                    <label class="form-check-label" for="read_puzzle">
                        <span>read_puzzle</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read-only access to read puzzle information</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="write_puzzle" name="write_puzzle">
                    <label class="form-check-label" for="write_puzzle">
                        <span>write_puzzle</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants create, edit, and delete access to any puzzle</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="create_puzzle" name="create_puzzle">
                    <label class="form-check-label" for="create_puzzle">
                        <span>create_puzzle</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to create a puzzle</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="edit_puzzle" name="edit_puzzle">
                    <label class="form-check-label" for="edit_puzzle">
                        <span>edit_puzzle</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to edit any puzzle</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="delete_puzzle" name="delete_puzzle">
                    <label class="form-check-label" for="delete_puzzle">
                        <span>delete_puzzle</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to delete any puzzle</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="wishlist" name="wishlist">
                    <label class="form-check-label" for="wishlist">
                        <span>wishlist</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read, create, edit, and delete access to your wishlist</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="read_wishlist" name="read_wishlist">
                    <label class="form-check-label" for="read_wishlist">
                        <span>read_wishlist</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read-only access to your wishlist</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="write_wishlist" name="write_wishlist">
                    <label class="form-check-label" for="write_wishlist">
                        <span>write_wishlist</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants create, edit, and delete access to your wishlist</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="create_wishlist" name="create_wishlist">
                    <label class="form-check-label" for="create_wishlist">
                        <span>create_wishlist</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to create a puzzle on your wishlist</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="edit_wishlist" name="edit_wishlist">
                    <label class="form-check-label" for="edit_wishlist">
                        <span>edit_wishlist</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to edit a puzzle on your wishlist</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="delete_wishlist" name="delete_wishlist">
                    <label class="form-check-label" for="delete_wishlist">
                        <span>delete_wishlist</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to delete a puzzle on your wishlist</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="user_inventory" name="user_inventory">
                    <label class="form-check-label" for="user_inventory">
                        <span>user_inventory</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read, add, edit, and delete access to your user inventory</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="read_user_inventory" name="read_user_inventory">
                    <label class="form-check-label" for="read_user_inventory">
                        <span>read_user_inventory</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read-only access to your user inventory</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="write_user_inventory" name="write_user_inventory">
                    <label class="form-check-label" for="write_user_inventory">
                        <span>write_user_inventory</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants add, edit, and delete access to your user inventory</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="add_user_inventory" name="add_user_inventory">
                    <label class="form-check-label" for="add_user_inventory">
                        <span>add_user_inventory</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to add a puzzle to your user inventory</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="edit_user_inventory" name="edit_user_inventory">
                    <label class="form-check-label" for="edit_user_inventory">
                        <span>edit_user_inventory</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to edit a puzzle in your user inventory</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="remove_user_inventory" name="remove_user_inventory">
                    <label class="form-check-label" for="remove_user_inventory">
                        <span>remove_user_inventory</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to remove a puzzle from your user inventory</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="misc" name="misc">
                    <label class="form-check-label" for="misc">
                        <span>misc</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read and write access to brands, statuses, categories, and more</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="read_misc" name="read_misc">
                    <label class="form-check-label" for="read_misc">
                        <span>read_misc</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants full read-only access to brands, statuses, categories, and more</span>
                    </label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="checkbox" value="" id="write_misc" name="write_misc">
                    <label class="form-check-label" for="write_misc">
                        <span>write_misc</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants create, edit, and delete access to brands, statuses, categories, and more</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="create_misc" name="create_misc">
                    <label class="form-check-label" for="create_misc">
                        <span>create_misc</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to create brands, statuses, categories, and more</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="edit_misc" name="edit_misc">
                    <label class="form-check-label" for="edit_misc">
                        <span>edit_misc</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to edit brands, statuses, categories, and more</span>
                    </label>
                </div>
                <div class="form-check ms-5">
                    <input class="form-check-input" type="checkbox" value="" id="delete_misc" name="delete_misc">
                    <label class="form-check-label" for="delete_misc">
                        <span>delete_misc</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants the ability to delete brands, statuses, categories, and more</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="read" name="read">
                    <label class="form-check-label" for="read">
                        <span>read</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants read-only access to the API</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="write" name="write">
                    <label class="form-check-label" for="write">
                        <span>write</span>
                        <br>
                        <span class="text-body-secondary p-0">Grants <span class="text-danger fw-bolder fst-italic"><i class="bi bi-exclamation-triangle"></i> full read and write access <i class="bi bi-exclamation-triangle"></i></span> to the API</span>
                    </label>
                </div>
            </div>
            <input class="btn btn-success" type="submit" name="submit" id="submit">
        </form>
    </div>
</div>

<script>
    let form = $('#form');
    let submit = $('#submit');

    let read_profile = $('#read_profile');
    let read_puzzle = $('#read_puzzle');
    let read_wishlist = $('#read_wishlist');
    let read_user_inventory = $('#read_user_inventory');
    let read = $('#read');
    let write_profile = $('#write_profile');
    let profile = $('#profile');
    let create_puzzle = $('#create_puzzle');
    let edit_puzzle = $('#edit_puzzle');
    let delete_puzzle = $('#delete_puzzle');
    let write_puzzle = $('#write_puzzle');
    let puzzle = $('#puzzle');
    let create_wishlist = $('#create_wishlist');
    let edit_wishlist = $('#edit_wishlist');
    let delete_wishlist = $('#delete_wishlist');
    let write_wishlist = $('#write_wishlist');
    let wishlist = $('#wishlist');
    let add_user_inventory = $('#add_user_inventory');
    let edit_user_inventory = $('#edit_user_inventory');
    let remove_user_inventory = $('#remove_user_inventory');
    let write_user_inventory = $('#write_user_inventory');
    let user_inventory = $('#user_inventory');
    let read_misc = $('#read_misc');
    let write_misc = $('#write_misc');
    let create_misc = $('#create_misc');
    let edit_misc = $('#edit_misc');
    let delete_misc = $('#delete_misc');
    let misc = $('#misc');
    let write = $('#write');

    let profilegrp = {
        master: profile,
        children: [
            read_profile,
            write_profile,
        ]
    }

    let writepuzzlegrp = {
        master: write_puzzle,
        children: [
            create_puzzle,
            edit_puzzle,
            delete_puzzle,
        ]
    }

    let puzzlegrp = {
        master: puzzle,
        children: [
            read_puzzle,
            writepuzzlegrp
        ]
    }

    let writewishlistgrp = {
        master: write_wishlist,
        children: [
            create_wishlist,
            edit_wishlist,
            delete_wishlist,
        ]
    }

    let wishlistgrp = {
        master: wishlist,
        children: [
            read_wishlist,
            writewishlistgrp
        ]
    }

    let writeuserinventorygrp = {
        master: write_user_inventory,
        children: [
            add_user_inventory,
            edit_user_inventory,
            remove_user_inventory,
        ]
    }

    let userinventorygrp = {
        master: user_inventory,
        children: [
            read_user_inventory,
            writeuserinventorygrp
        ]
    }

    let writemiscgrp = {
        master: write_misc,
        children: [
            create_misc,
            edit_misc,
            delete_misc,
        ]
    }

    let miscgrp = {
        master: misc,
        children: [
            read_misc,
            writemiscgrp,
        ]
    }

    let readgrp = {
        master: read,
        children: [
            read_profile,
            read_puzzle,
            read_wishlist,
            read_user_inventory,
            read_misc,
        ]
    };

    let writegrp = {
        master: write,
        children: [
            readgrp,
            profilegrp,
            puzzlegrp,
            wishlistgrp,
            userinventorygrp,
            writemiscgrp,
            miscgrp
        ]
    }

    let groups = [
        profilegrp,
        writepuzzlegrp,
        puzzlegrp,
        writewishlistgrp,
        wishlistgrp,
        writeuserinventorygrp,
        userinventorygrp,
        writemiscgrp,
        miscgrp,
        readgrp,
        writegrp
    ]

    function checkAll(group) {
        group.children.forEach(element => {
            if (element.master == null) {
                element.prop('checked', true)
                element.prop('disabled', true)
            } else {
                element.master.prop('checked', true)
                element.master.prop('disabled', true)
                checkAll(element)
            }
        });
    }

    function uncheck(group) {
        group.children.forEach(element => {
            if (element.master == null) {
                element.prop('checked', false);
                element.prop('disabled', false);
            } else {
                element.master.prop('checked', false);
                element.master.prop('disabled', false);
                uncheck(element)
            }

            let filtered = groups.filter(grp => grp !== group);
            filtered.forEach(grp => {
                if (grp.children.includes(element) && grp.master.prop('checked') === true) {
                    if (element.master == null || element.children == null) {
                        element.prop('checked', true)
                        element.prop('disabled', true)
                    } else {
                        uncheck(element)
                    }
                }
            })
        });
    }

    groups.forEach(grp => {
        grp.master.on('change', function() {
            if ($(this).is(':checked')) checkAll(grp);
            else uncheck(grp);
        })
    })

    submit.on('click', function() {
        let disabled = $(':disabled');

        disabled.each(function () {
            $(this).prop('disabled', false);
        });
    })
</script>