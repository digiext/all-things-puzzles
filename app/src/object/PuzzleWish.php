<?php

namespace puzzlethings\src\object;

use puzzlethings\src\gateway\BrandGateway;

class PuzzleWish implements \JsonSerializable
{
    private int $id;
    private ?string $name, $upc;
    private ?int $userid, $pieces;
    private ?Brand $brand;

    public function __construct(int $id, ?int $userid, ?string $name, ?int $pieces, ?Brand $brand, ?string $upc)
    {
        $this->id = $id;
        $this->userid = $userid;
        $this->name = $name;
        $this->pieces = $pieces;
        $this->brand = $brand;
        $this->upc = $upc;
    }

    public static function of($res, $db): PuzzleWish
    {
        $brand = (new BrandGateway($db))->findById($res['brandid']);

        return new PuzzleWish($res['wishid'], $res['userid'], $res['puzname'], $res['pieces'], $brand, $res['upc']);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPieces(): ?int
    {
        return $this->pieces;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function getUpc(): ?string
    {
        return $this->upc;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'userid' => $this->userid,
            'name' => $this->name,
            'pieces' => $this->pieces,
            'brand' => $this->brand,
            'upc' => $this->upc,
        ];
    }
}
