<?php

require_once __DIR__ . "/../api_utils.php";

require_auth();

$permissions = [];
foreach (PERM_LOOKUP as $int => $name) {
    $permissions[$name] = has_permissions($int);
}

success([
    'permissions' => $permissions,
]);
