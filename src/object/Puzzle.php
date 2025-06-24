<?php
namespace puzzlethings\src\object;
use DateTime;
use Exception;
use puzzlethings\src\gateway\BrandGateway;
use puzzlethings\src\gateway\DispositionGateway;
use puzzlethings\src\gateway\OwnershipGateway;

class Puzzle implements \JsonSerializable {
    private int $id;
    private ?string $name, $picture, $upc;
    private ?int $pieces, $source, $location;
    private ?Brand $brand;
    private ?float $cost;
    private ?DateTime $acquired;
    private ?Ownership $ownership;
    private ?Disposition $disposition;

    public function __construct(int $id, ?string $name, ?int $pieces, ?Brand $brand, ?float $cost, ?DateTime $acquired, ?int $source, ?Ownership $ownership, ?int $location, ?Disposition $disposition, ?string $picture, ?string $upc) {
        $this->id = $id;
        $this->name = $name;
        $this->pieces = $pieces;
        $this->brand = $brand;
        $this->cost = $cost;
        $this->acquired = $acquired;
        $this->source = $source;
        $this->ownership = $ownership;
        $this->location = $location;
        $this->disposition = $disposition;
        $this->picture = $picture;
        $this->upc = $upc;
    }

    public static function of($res, $db): Puzzle {
        try {
            $dateAcquired = new DateTime($res['dateacquired']);
        } catch (Exception) {
            $dateAcquired = null;
        }
        $brand = (new BrandGateway($db))->findById($res['brandid']);
        $ownership = (new OwnershipGateway($db))->findById($res['ownershipid']);
        $disposition = (new DispositionGateway($db))->findById($res['dispositionid']);

        return new Puzzle($res['puzzleid'], $res['puzname'], $res['pieces'], $brand, $res['cost'], $dateAcquired, $res['sourceid'], $ownership, $res['locationid'], $disposition, $res['pictureurl'], $res['upc']);
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
            'ownership' => $this->ownership,
            'location' => $this->location,
            'disposition' => $this->disposition,
            'pictureurl' => $this->picture,
            'upc' => $this->upc,
        ];
    }
}