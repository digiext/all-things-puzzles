<?php

require_once __DIR__ . "/../api_utils.php";

require_auth();
success([
    'permissions' => [
        'read' => has_permissions(PERM_READ),
        'write' => has_permissions(PERM_WRITE),
        'read_profile' => has_permissions(PERM_READ_PROFILE),
        'write_profile' => has_permissions(PERM_WRITE_PROFILE),
        'profile' => has_permissions(PERM_PROFILE),
        'read_puzzle' => has_permissions(PERM_READ_PUZZLE),
        'create_puzzle' => has_permissions(PERM_CREATE_PUZZLE),
        'edit_puzzle' => has_permissions(PERM_EDIT_PUZZLE),
        'delete_puzzle' => has_permissions(PERM_DELETE_PUZZLE),
        'write_puzzle' => has_permissions(PERM_WRITE_PUZZLE),
        'puzzle' => has_permissions(PERM_PUZZLE),
        'read_wishlist' => has_permissions(PERM_READ_WISHLIST),
        'create_wishlist' => has_permissions(PERM_CREATE_WISHLIST),
        'edit_wishlist' => has_permissions(PERM_EDIT_WISHLIST),
        'delete_wishlist' => has_permissions(PERM_DELETE_WISHLIST),
        'write_wishlist' => has_permissions(PERM_WRITE_WISHLIST),
        'wishlist' => has_permissions(PERM_WISHLIST),
        'read_user_inventory' => has_permissions(PERM_READ_USER_INVENTORY),
        'create_user_inventory' => has_permissions(PERM_ADD_USER_INVENTORY),
        'edit_user_inventory' => has_permissions(PERM_EDIT_USER_INVENTORY),
        'delete_user_inventory' => has_permissions(PERM_REMOVE_USER_INVENTORY),
        'write_user_inventory' => has_permissions(PERM_WRITE_USER_INVENTORY),
        'user_inventory' => has_permissions(PERM_USER_INVENTORY),
    ]
]);
