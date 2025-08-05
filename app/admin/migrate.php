<?php
global $db, $current;
include '../util/function.php';
require '../util/db.php';

use puzzlethings\src\gateway\OwnershipGateway;
use puzzlethings\src\object\Ownership;

//If Not Logged In Reroute to index.php
if (!isLoggedIn()) {
    header("Location: ../home.php");
}

if (!isAdmin()) {
    header("Location: ../admin.php");
}

$title = 'Migration Dashboard';
include '../header.php';
include '../nav.php';

$failAlert = fn($alert) => die("<div class='alert alert-danger'>$alert</div>");

if (!file_exists(__DIR__ . '/../../migrate/versions.json')) {
    $failAlert('Could not read versions file! Check permissions!');
}

$allversions = json_decode(file_get_contents('https://raw.githubusercontent.com/digiext/all-things-puzzles/refs/heads/main/migrate/versions.json') ?? [], true) ?? [];
$localversions = json_decode(file_get_contents(__DIR__ . '/../../migrate/versions.json'), true);

$localversions = array_reduce($localversions, function ($res, $ver) {
    $res[$ver['id']] = $ver;
    return $res;
});

krsort($localversions);
krsort($allversions);

if (!file_exists(__DIR__ . '/../../migrate/migration.json')) {
    $initMigrationWrite = file_put_contents(__DIR__ . '/../../migrate/migration.json', json_encode([
        'current' => $localversions[array_key_first($localversions)]['id'],
        'previous' => []
    ], JSON_PRETTY_PRINT));

    if (!$initMigrationWrite) {
        $failAlert('Could not write initial migration file! Check permissions!');
    }
}

$migrationFile = json_decode(file_get_contents(__DIR__ . '/../../migrate/migration.json'), true);
$current = $migrationFile['current'];
$previous = $migrationFile['previous'];

$latest = $allversions[array_key_first($allversions)] ?? ['id' => $current, 'version' => 'Error'];
?>

<div class="alert alert-danger" id="alertBox">Alert</div>
<div class="container-fluid mb-2 mt-4 hstack justify-content-between">
    <h4 class="justify-content-start">Migration Dashboard</h4>
    <div class="d-grid gap-2 d-md-flex">
        <a class="btn btn-primary justify-content-end" href="../admin.php">Admin</a>
    </div>
</div>
<hr class="mx-2">
<div class="mx-4 hstack align-items-start">
    <div class="col-md-3 col-sm-12">
        <div class="rounded-3 bg-body-tertiary px-3 m-2 mt-3" id="currentVersion">
            <strong>Latest Version:</strong> <?php echo $latest['version'] != 'Error' ? ("v" . $latest['version']) : $latest['version']; ?>
        </div>
        <div class="rounded-3 bg-body-tertiary px-3 m-2" id="currentVersion">
            <strong>Current Version:</strong> <?php echo $localversions[$current]['version'] != null ? ("v" . $localversions[$current]['version']) : "Unknown ($current)"; ?>
        </div>
        <div class="rounded-3 bg-body-tertiary px-3 m-2" id="previousVersions">
            <strong>Previous Versions:</strong>
            <?php
            if ($previous == []) {
                echo "None";
            } else {
                echo "<ul>";
                foreach ($previous as $version) {
                    $ver = $localversions[$version];
                    if ($ver != null) {
                        echo "<li>v" . $ver['version'] . "</li>";
                    } else {
                        echo "<li>Unknown ($version)</li>";
                    }
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>
    <div class="vr"></div>
    <div class="px-3 w-100 overflow-y-scroll">
        <?php
            $latestloc = $localversions[array_key_first($localversions)];
            if ($current < $latestloc['id']) {
                echo "<button class='btn btn-primary mt-3' onclick='migrateFull()' id='migratelatest'>Migrate Until Latest Local Version (v" . $latest['version'] . ")</button>";
            }

            foreach ($allversions as $version) {
                $id = $version['id'];
                $greater = $current >= $id;
                $description = $version['description'];
                $sql = $version['sql'] ?? '';

                if ($sql == null) {
                    $sqlbtn = "";
                } else if (file_exists(__DIR__ . "/../../migrate/$sql")) {
                    $sqlbtn = "<button class='btn btn-primary mx-1 migrate-button' onclick='migrateSQL($id)'><span>Migrate SQL</span></button>";
                } else {
                    $sqlbtn = "<button class='btn btn-warning mx-1 migrate-button' onclick='alert(`SQL file not found on server!`)'><span>Migrate SQL</span></button>";
                }
                $ver = $version['version'];

                echo
                "<div class='card my-3 border-" . ($greater ? "success" : "danger") ."' id='v$id'>
                    <div class='card-header text-bg-" . ($greater ? "success" : "danger") . "'>v$ver</div>
                    <div class='card-body'>
                        <p class='card-text'>$description</p>
                        <a href='https://github.com/digiext/all-things-puzzles/releases/tag/v$ver/' class='btn btn-primary'>Release Page</a>
                        $sqlbtn
                    </div>
                </div>";
            }
        ?>
    </div>
</div>

<script>
    let currentVersion = $('#currentVersion');
    let previousVersions = $('#previousVersions');
    let alertBox = $('#alertBox');
    alertBox.hide();



    function migrateFull() {
        let migrateButton = $('#migratelatest');
        migrateButton.prepend(`<span class="spinner-border spinner-border-sm migrate-status" role="status"></span> `)

        $.ajax('migratesql.php', {
            method: 'POST',
            data: {
                'id': 'latest'
            },
            success: (res) => {
                currentVersion.html(`<strong>Current SQL Version: </strong> Latest`)
                $('.card.border-danger').each(function () {
                    let cardHeader = $(this).find('.card-header')

                    $(this).addClass('border-success')
                    $(this).removeClass('border-danger border-warning')

                    cardHeader.addClass('text-bg-success')
                    cardHeader.removeClass('text-bg-danger text-bg-warning')

                    migrateButton.find('.migrate-status').remove()
                })
            },
            error: (err) => {
                alertBox.text(err.responseText);
                alertBox.show(200)

                migrateButton.find('.migrate-status').remove()
            }
        })
    }

    function migrateSQL(id) {
        let card = $(`#v${id}`)
        let cardHeader = card.find('.card-header')
        let migrateButton = card.find('.migrate-button');

        card.addClass('border-warning')
        card.removeClass('border-danger border-success')

        cardHeader.addClass('text-bg-warning')
        cardHeader.removeClass('text-bg-danger text-bg-success')

        migrateButton.prepend(`<span class="spinner-border spinner-border-sm migrate-status" role="status"></span> `)

        $.ajax('migratesql.php', {
            method: 'POST',
            data: {
                'id': id,
            },
            success: (res) => {
                card.addClass('border-success')
                card.removeClass('border-warning')

                cardHeader.addClass('text-bg-success')
                cardHeader.removeClass('text-bg-warning')

                migrateButton.find('.migrate-status').remove()

                currentVersion.html(`<strong>Current SQL Version: </strong> ${cardHeader.text()}`)
            },
            error: (err) => {
                card.addClass('border-danger')
                card.removeClass('border-warning')

                cardHeader.addClass('text-bg-danger')
                cardHeader.removeClass('text-bg-warning')

                migrateButton.find('.migrate-status').remove()

                alertBox.text(err.responseText);
                alertBox.show(200)

                setTimeout(() => {
                    alertBox.hide(200)
                }, 5000)
            }
        })
    }
</script>
