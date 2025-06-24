<?php
namespace puzzlethings\object;
use DateTime;

class Puzzle {
    private ?int $id = null;
    private ?string $name = null;
    private ?int $pieces = null;
    private ?int $brand = null;
    private ?float $cost = null;
    private ?DateTime $acquired = null;
    private ?int $source = null;
    private ?int $ownership = null;
    private ?int $location = null;
    private ?int $disposition = null;
    private ?string $picture = null;

    public function __construct($id) {
        $this->id = $id;
    }
}