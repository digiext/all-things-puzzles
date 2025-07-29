<?php
require_once __DIR__ . "/../util/db.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

function error(array $error, int $statusCode = 404): void
{
    http_response_code($statusCode);

    if (minimalInformation()) $json = json_encode($error);
    else $json = json_encode([
        SERVER_VERSION => VERSION,
        API => API_VERSION,
        ERROR => $error,
        HAS_ERROR => true,
        DATA => null,
    ]);

    echo $json;
}

function bad_request(Error $e): void
{
    error(array_merge(API_ERROR_BAD_REQUEST, ['error_message' => $e->getMessage()]), 400);
}

function wrong_method(array $accepted): void
{
    error(array_merge(API_ERROR_WRONG_METHOD, [ACCEPTED_METHODS => $accepted]), 405);
}

function database_error(): void
{
    error(API_ERROR_DATABASE, 500);
}

function success(mixed $data, int $statusCode = 200): void
{
    http_response_code($statusCode);

    if (minimalInformation()) $json = json_encode($data);
    else $json = json_encode([
        SERVER_VERSION => VERSION,
        API => API_VERSION,
        ERROR => null,
        HAS_ERROR => false,
        DATA => $data,
    ]);

    echo $json;
}

function success_with_pagination(mixed $data, int $count, int $statusCode = 200): void
{
    http_response_code($statusCode);
    if (minimalInformation()) $json = json_encode([
        DATA => $data,
        PREV => prev_page_link(),
        NEXT => next_page_link($data, $count),
    ]);
    else $json = json_encode([
        SERVER_VERSION => VERSION,
        API => API_VERSION,
        ERROR => null,
        HAS_ERROR => false,
        DATA => $data,
        PREV => prev_page_link(),
        NEXT => next_page_link($data, $count),
    ]);

    echo $json;
}

function search_options(string $defaultSort, array $filterSet): array {
    $maxperpage = $_GET[MAX_PER_PAGE] ?? 10;
    $page = $_GET[PAGE] ?? 0;
    $sort = $_GET[SORT] ?? $defaultSort;
    $sortDirection = $_GET[SORT_DIRECTION] ?? SQL_SORT_ASC;
    $filters = [];

    $query = [];
    parse_str($_SERVER['QUERY_STRING'] ?? "", $query);
    foreach ($query as $k => $v) {
        if (!in_array($k, $filterSet)) continue;

        $explodedv = explode(',', $v);
        if (count($explodedv) == 1) {
            $filters = array_merge($filters, [
                $k => $explodedv[0]
            ]);
        } else if (count($explodedv) == 2) {
            $filters = array_merge($filters, [
                $k => [$explodedv[0], $explodedv[1]]
            ]);
        } else {
            $filters = array_merge($filters, [
                $k => $v
            ]);
        }
    }

    return [
        MAX_PER_PAGE => $maxperpage,
        PAGE => $page,
        SORT => $sort,
        SORT_DIRECTION => $sortDirection,
        FILTERS => $filters
    ];
}

function minimalInformation(): bool
{
    return array_key_exists('min', $_GET);
}

function prev_page_link(): ?string
{
    $page = ($_GET[PAGE] ?? 0) - 1;
    if ($page < 0) return null;

    $query = [];
    parse_str($_SERVER['QUERY_STRING'] ?? "", $query);
    $query[PAGE] = $page == 0 ? null : $page;
    return api_link(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?" . http_build_query($query));
}

function next_page_link(array $data, int $count): ?string {
    if (($_GET[MAX_PER_PAGE] ?? 10) * ($_GET[PAGE] ?? 0) + count($data) >= $count) {
        return null;
    } else {
        $query = [];
        parse_str($_SERVER['QUERY_STRING'] ?? "", $query);
        $query[PAGE] = ($_GET[PAGE] ?? 0) + 1;
        return api_link(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?" . http_build_query($query));
    }
}

function api_link(string $path): string {
    return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $path;
}