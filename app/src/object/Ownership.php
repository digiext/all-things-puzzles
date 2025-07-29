<?php
namespace puzzlethings\src\object;

class Ownership implements \JsonSerializable {
    private int $id;
    private ?string $desc;

    public function __construct(int $id, ?string $desc) {
        $this->id = $id;
        $this->desc = $desc;
    }

    public static function of($res): Ownership {
        return new Ownership($res['ownershipid'], $res['ownershipdesc']);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->desc;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "description" => $this->desc,
        ];
    }
}
