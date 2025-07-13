<?php

namespace puzzlethings\src\object;

class Location implements \JsonSerializable
{
    private int $id;
    private ?string $desc;

    public function __construct(int $id, ?string $desc)
    {
        $this->id = $id;
        $this->desc = $desc;
    }

    public static function of(mixed $res): Location
    {
        return new Location($res["locationid"], $res["locationdesc"]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDescription(): ?string
    {
        return $this->desc;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "desc" => $this->desc
        ];
    }
}
