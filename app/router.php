<?php
$req = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', $path);

if (str_starts_with($path, "/api")) {
    include __DIR__ . '/util/api_constants.php';
    // API Rewrites

    if (preg_match("/^\/api\/(brand|disposition|ownership|puzzle|status|user)\/(\d+)\/?(\S*)$/", $path, $matches)) {
        $apipath = $matches[1];
        $id = $matches[2];
        $extra = $matches[3];

        if (!empty($extra) && !str_ends_with($extra, "/")) {
            $extra .= '/';
        }

        if ($req == GET) {
            $_GET['id'] = $id;
        } else if ($req == POST) {
            $_POST['id'] = $id;
        }

        error_log("Rerouting " . print_r($path, true) . " to api/$apipath/$extra" . "index.php?id=$id");
        include "api/$apipath/$extra/index.php";
    } else {
        error_log("Not rerouting $path");
        return false;
    }
} else {
    error_log("Not rerouting $path");
    return false;
}
