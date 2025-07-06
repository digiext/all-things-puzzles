<?php

namespace puzzlethings\src\object;

use puzzlethings\src\gateway\StatusGateway;
use puzzlethings\src\gateway\OwnershipGateway;
use puzzlethings\src\gateway\UserGateway;
use puzzlethings\src\gateway\PuzzleGateway;

class UserPuzzle implements \JsonSerializable
{
    private int $id;
    private ?string $startdate, $enddate;
    private ?int $missingpieces, $totaldays;
    private ?float $difficultyrating, $qualityrating;
    private ?User $user;
    private ?Puzzle $puzzle;
    private ?Status $status;
    private ?Ownership $ownership;



    public function __construct(int $id, ?User $user, ?Puzzle $puzzle, ?Status $status, ?int $missingpieces, ?string $startdate, ?string $enddate, ?int $totaldays, ?float $difficultyrating, ?float $qualityrating, ?Ownership $ownership)
    {
        $this->id = $id;
        $this->user = $user;
        $this->puzzle = $puzzle;
        $this->status = $status;
        $this->missingpieces = $missingpieces;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->totaldays = $totaldays;
        $this->difficultyrating = $difficultyrating;
        $this->qualityrating = $qualityrating;
        $this->ownership = $ownership;
    }

    public static function of($res, $db): UserPuzzle
    {
        $user = (new UserGateway($db))->findById($res['userid']);
        $puzzle = (new PuzzleGateway($db))->findById($res['puzzleid']);
        $status = (new StatusGateway($db))->findById($res['statusid']);
        $ownership = (new OwnershipGateway($db))->findById($res['ownershipid']);

        return new UserPuzzle($res['userinvid'], $user, $puzzle, $status, $res['missingpieces'], $res['startdate'], $res['enddate'], $res['totaldays'], $res['difficultyrating'], $res['qualityrating'], $ownership);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function getPuzzle(): ?Puzzle
    {
        return $this->puzzle;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function getMissingPieces(): ?int
    {
        return $this->missingpieces;
    }

    public function getStart(): ?string
    {
        return $this->startdate;
    }

    public function getEnd(): ?string
    {
        return $this->enddate;
    }

    public function getTotal(): ?int
    {
        return $this->totaldays;
    }

    public function getDifficulty(): ?float
    {
        return $this->difficultyrating;
    }

    public function getQuality(): ?float
    {
        return $this->qualityrating;
    }

    public function getOwnership(): ?Ownership
    {
        return $this->ownership;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'puzzle' => $this->puzzle,
            'status' => $this->status,
            'missingpieces' => $this->missingpieces,
            'start' => $this->startdate,
            'end' => $this->enddate,
            'totaldays' => $this->totaldays,
            'difficultyrating' => $this->difficultyrating,
            'qualityrating' => $this->qualityrating,
            'ownership' => $this->ownership,
        ];
    }
}
