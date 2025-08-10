<?php
namespace puzzlethings\src\object;

class Disposition implements \JsonSerializable {
    private int $id;
    private ?string $desc;

    public function __construct(?int $id, ?string $desc) {
        $this->id = $id;
        $this->desc = $desc;
    }

    public static function of(mixed $res): Disposition {
        return new Disposition($res['dispositionid'], $res['dispositiondesc']);
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getDescription(): ?string {
        return $this->desc;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "description" => $this->desc
        ];
    }
}