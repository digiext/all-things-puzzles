<?php

namespace puzzlethings\src\object;

class Category implements \JsonSerializable
{
    private int $id;
    private ?string $desc;

    public function __construct(int $id, ?string $desc)
    {
        $this->id = $id;
        $this->desc = $desc;
    }

    public static function of(mixed $res): Category
    {
        return new Category($res["categoryid"], $res["categorydesc"]);
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
