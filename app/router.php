<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', $path);

if (str_starts_with($path, "/api")) {
    // API Rewrites

    if (preg_match("/^\/api\/(brand|disposition|ownership|puzzle|status|user)\/([^\/]+)\/?$/", $path, $matches)) {
        $apipath = $matches[1];
        $id = $matches[2];
        $_GET['id'] = $id;
        include "api/$apipath/index.php";
        error_log("Rerouting " . print_r($path, true) . " to api/$apipath/index.php?id=$id");
    } else {
        error_log("Not rerouting $path");
        return false;
    }
} else {
    error_log("Not rerouting $path");
    return false;
}
