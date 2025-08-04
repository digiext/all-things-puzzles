<?php
$req = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', $path);

if (str_starts_with($path, "/api")) {
    include __DIR__ . '/util/api_constants.php';
    // API Rewrites

    if (preg_match("/^\/api\/(brand|category|disposition|location|ownership|puzzle|source|status|user|userinventory|wishlist)\/(\d+)\/?(\S*)$/", $path, $matches)) {
        $apipath = $matches[1];
        $id = $matches[2];
        $extra = $matches[3];

        if (!empty($extra) && !str_ends_with($extra, "/")) {
            $extra .= '/';
        }

        if ($req == GET) {
            $_GET['id'] = $id;
        } else if ($req == POST || $req == PUT || $req == DELETE) {
            $_POST['id'] = $id;
        }

        error_log("Rerouting " . print_r($path, true) . " to api/$apipath/$extra" . "index.php?id=$id");
        if (file_exists("api/$apipath/$extra/index.php")) {
            include "api/$apipath/$extra/index.php";
        } else {
            http_response_code(404);
        }
    } else {
        if ((str_ends_with($path, '/') && file_exists("$path/index.php"))) {
            error_log("Not rerouting $path");
            include "$path/index.php";
        } else if (file_exists($path)) {
            error_log("Not rerouting $path");
            return false;
        } else {
            http_response_code(404);
        }
    }
} else {
    if ((str_ends_with($path, '/') && file_exists("$path/index.php"))) {
        error_log("Not rerouting $path");
        include "$path/index.php";
    } else if (file_exists($path)) {
        error_log("Not rerouting $path");
        return false;
    } else {
        http_response_code(404);
    }
}
