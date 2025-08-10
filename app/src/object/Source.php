<?php

namespace puzzlethings\src\object;

class Source implements \JsonSerializable
{
    private int $id;
    private ?string $desc;

    public function __construct(int $id, ?string $description)
    {
        $this->id = $id;
        $this->desc = $description;
    }

    public static function of(mixed $res): Source
    {
        return new Source($res["sourceid"], $res["sourcedesc"]);
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
            "description" => $this->desc
        ];
    }
}
