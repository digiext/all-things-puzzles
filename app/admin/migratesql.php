<?php
global $db;
include '../util/function.php';
require '../util/db.php';

const MIGRATION = __DIR__ . '/../migrate/migration.json';

$id = $_POST['id'];
$versions = json_decode(file_get_contents(__DIR__ . '/../migrate/versions.json'), true);
$versions = array_reduce($versions, function ($res, $ver) {
    $res[$ver['id']] = $ver;
    return $res;
});

ksort($versions);

if (!file_exists(MIGRATION)) {
    $initMigrationWrite = file_put_contents(MIGRATION, json_encode([
        'current' => 1000,
        'previous' => []
    ], JSON_PRETTY_PRINT));

    if (!$initMigrationWrite) {
        http_response_code(500);
        die('Could not write initial migration file! Check permissions!');
    }
}

$migrationFile = json_decode(file_get_contents(MIGRATION), true);
$current = $migrationFile['current'];
$previous = $migrationFile['previous'];


$db->beginTransaction();

if ($id === 'latest') {
    // do updates until latest

    foreach ($versions as $version) {
        $greater = $version['id'] > $current;
        if ($greater) {
            $sqlFile = __DIR__ . '/../migrate/' . $version['sql'];
            if (!file_exists($sqlFile)) {
                http_response_code(500);
                die("SQL Migration file not found! Check permissions!");
            }

            try {
                error_log("Attempting to migrate SQL for v" . $version['version']);
                $sql = file_get_contents($sqlFile);
                $db->query($sql);
            } catch (PDOException $e) {
                http_response_code(500);
                error_log($e->getMessage());
                $db->rollBack();
                die("SQL Migration failed (v" . $version['version'] . ")! Check console! Rolling back changes...");
            }
        } else {
            error_log("Skipping SQL migration for v" . $version['version']);
        }
    }

    $db->commit();

    $migrationFile['previous'] = array_merge($previous, [$current]);
    $migrationFile['current'] = array_key_last($versions);
    file_put_contents(MIGRATION, json_encode($migrationFile, JSON_PRETTY_PRINT));
    die();
} else {
    $ver = $versions[$id];
    $sqlFile = __DIR__ . '/../migrate/' . $ver['sql'];
    if (!file_exists($sqlFile)) {
        http_response_code(500);
        die("SQL Migration file not found! Check permissions!");
    }

    try {
        $sql = file_get_contents($sqlFile);
        $db->query($sql);
    } catch (PDOException $e) {
        http_response_code(500);
        error_log($e->getMessage());
        $db->rollBack();
        die("SQL Migration failed! Check console! Rolling back changes...");
    }

    $db->commit();

    $migrationFile['previous'] = array_merge($previous, [$current]);
    $migrationFile['current'] = $ver['id'];
    file_put_contents(MIGRATION, json_encode($migrationFile, JSON_PRETTY_PRINT));
    die();
}