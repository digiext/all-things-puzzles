<?php
namespace puzzlethings\src\object;
use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\gateway\LocationGateway;
use puzzlethings\src\gateway\SourceGateway;

class Puzzle implements \JsonSerializable {
    private int $id;
    private ?string $name, $acquired, $picture, $upc;
    private ?int $pieces;
    private ?Source $source;
    private ?Location $location;
    private ?Brand $brand;
    private ?float $cost;
    private ?Disposition $disposition;

    public function __construct(int $id, ?string $name, ?int $pieces, ?Brand $brand, ?float $cost, ?string $acquired, ?Source $source, ?Location $location, ?Disposition $disposition, ?string $picture, ?string $upc) {
        $this->id = $id;
        $this->name = $name;
        $this->pieces = $pieces;
        $this->brand = $brand;
        $this->cost = $cost;
        $this->acquired = $acquired;
        $this->source = $source;
        $this->location = $location;
        $this->disposition = $disposition;
        $this->picture = $picture;
        $this->upc = $upc;
    }

    public static function of($res, $db): Puzzle {
        $brand = new BrandGateway($db)->findById($res['brandid']);
        $disposition = new DispositionGateway($db)->findById($res['dispositionid']);
        $source = new SourceGateway($db)->findById($res['sourceid']);
        $location = new LocationGateway($db)->findById($res['locationid']);

        return new Puzzle($res['puzzleid'], $res['puzname'], $res['pieces'], $brand, $res['cost'], $res['dateacquired'], $source, $location, $disposition, $res['pictureurl'], $res['upc']);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getPieces(): ?int {
        return $this->pieces;
    }

    public function getBrand(): ?Brand {
        return $this->brand;
    }

    public function getCost(): ?float {
        return $this->cost;
    }

    public function getAcquired(): ?string {
        return $this->acquired;
    }

    public function getSource(): ?Source {
        return $this->source;
    }

    public function getLocation(): ?Location {
        return $this->location;
    }

    public function getDisposition(): ?Disposition {
        return $this->disposition;
    }

    public function getPicture(): ?string {
        return $this->picture;
    }

    public function getUpc(): ?string {
        return $this->upc;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'pieces' => $this->pieces,
            'brand' => $this->brand,
            'cost' => $this->cost,
            'acquired' => $this->acquired,
            'source' => $this->source,
            'location' => $this->location,
            'disposition' => $this->disposition,
            'pictureurl' => $this->picture,
            'upc' => $this->upc,
        ];
    }
}