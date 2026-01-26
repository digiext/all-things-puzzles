<?php
use puzzlethings\src\gateway\PuzzleGateway;
use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\gateway\UserGateway;
use puzzlethings\src\gateway\UserPuzzleGateway;

require_once __DIR__ . "/../api_utils.php";

$req = $_SERVER['REQUEST_METHOD'];
if ($req == GET) {
    try {
        global $db;
        $userGateway = new UserGateway($db);
        $puzzleGateway = new PuzzleGateway($db);
        $upuzGateway = new UserPuzzleGateway($db);
        $statusGateway = new StatusGateway($db);

        $completeStatus = $statusGateway->findByName('Complete');

        try {
            success([
                'puzzle_count' => $puzzleGateway->count(),
                'puzzles_finished' => $upuzGateway->count([UINV_FILTER_STATUS => $completeStatus]),
                'user_count' => $userGateway->count(),
            ]);
        } catch (Exception $e) {
            error(API_ERROR_SERVER);
        }
    } catch (Error $e) {
        bad_request($e);
    }
} else {
    wrong_method([GET]);
}