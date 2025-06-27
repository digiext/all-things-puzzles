<?php

namespace puzzlethings\src\object;

class Source implements \JsonSerializable
{
    private int $id;
    private ?string $source;

    public function __construct(int $id, ?string $source)
    {
        $this->id = $id;
        $this->source = $source;
    }

    public static function of(mixed $res): Source
    {
        return new Source($res["sourceid"], $res["sourcedesc"]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getSource(): ?string
    {
        return $this->source;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "name" => $this->source
        ];
    }
}
