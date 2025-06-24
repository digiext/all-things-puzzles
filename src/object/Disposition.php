<?php
namespace puzzlethings\src\object;

class Disposition implements \JsonSerializable {
    private int $id;
    private ?string $name;

    public function __construct(?int $id, ?string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function of(mixed $res): Disposition {
        return new Disposition($res['dispositionid'], $res['dispositiondesc']);
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "name" => $this->name
        ];
    }
}